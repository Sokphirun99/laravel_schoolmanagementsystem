pipeline {
    agent {
        kubernetes {
            yaml """
            apiVersion: v1
            kind: Pod
            spec:
              containers:
              - name: php
                image: php:8.1-fpm
                command:
                - cat
                tty: true
              - name: docker
                image: docker:20.10.16-dind
                command:
                - cat
                tty: true
                volumeMounts:
                - name: docker-sock
                  mountPath: /var/run/docker.sock
              volumes:
              - name: docker-sock
                hostPath:
                  path: /var/run/docker.sock
            """
        }
    }

    environment {
        APP_NAME = 'laravel-school-management'
        DOCKER_REGISTRY = 'docker.io/yourusername'  // Update with your Docker Hub username or private registry
        DOCKER_IMAGE = "${DOCKER_REGISTRY}/${APP_NAME}"
        KUBERNETES_NAMESPACE = 'school-management'
        KUBERNETES_DEPLOYMENT = 'school-management-app'
    }

    stages {
        stage('Checkout') {
            steps {
                checkout scm
            }
        }

        stage('Composer Install') {
            steps {
                container('php') {
                    sh 'apt-get update && apt-get install -y git zip unzip libzip-dev'
                    sh 'docker-php-ext-install zip pdo pdo_mysql'
                    sh 'curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer'
                    sh 'composer install --no-interaction --optimize-autoloader --no-dev'
                }
            }
        }

        stage('Run Tests') {
            steps {
                container('php') {
                    sh 'cp .env.example .env.testing'
                    sh 'php artisan key:generate --env=testing'
                    sh 'php artisan test'
                }
            }
        }

        stage('Build Docker Image') {
            steps {
                container('docker') {
                    sh 'docker build -t ${DOCKER_IMAGE}:${BUILD_NUMBER} -t ${DOCKER_IMAGE}:latest .'
                }
            }
        }

        stage('Push Docker Image') {
            steps {
                container('docker') {
                    withCredentials([usernamePassword(credentialsId: 'docker-registry-credentials', passwordVariable: 'DOCKER_PASSWORD', usernameVariable: 'DOCKER_USERNAME')]) {
                        sh 'echo $DOCKER_PASSWORD | docker login $DOCKER_REGISTRY -u $DOCKER_USERNAME --password-stdin'
                        sh 'docker push ${DOCKER_IMAGE}:${BUILD_NUMBER}'
                        sh 'docker push ${DOCKER_IMAGE}:latest'
                    }
                }
            }
        }

        stage('Deploy to Kubernetes') {
            steps {
                withCredentials([file(credentialsId: 'kubeconfig', variable: 'KUBECONFIG')]) {
                    sh '''
                        export KUBECONFIG=${KUBECONFIG}
                        # Update the image in the deployment
                        kubectl set image deployment/${KUBERNETES_DEPLOYMENT} ${KUBERNETES_DEPLOYMENT}=${DOCKER_IMAGE}:${BUILD_NUMBER} -n ${KUBERNETES_NAMESPACE}
                        # Check rollout status
                        kubectl rollout status deployment/${KUBERNETES_DEPLOYMENT} -n ${KUBERNETES_NAMESPACE}
                    '''
                }
            }
        }

        stage('Run Database Migrations') {
            steps {
                withCredentials([file(credentialsId: 'kubeconfig', variable: 'KUBECONFIG')]) {
                    sh '''
                        export KUBECONFIG=${KUBECONFIG}
                        # Run migrations in a separate pod
                        kubectl create job --from=cronjob/database-migrations database-migrations-${BUILD_NUMBER} -n ${KUBERNETES_NAMESPACE} || true
                        # Wait for job to complete
                        kubectl wait --for=condition=complete job/database-migrations-${BUILD_NUMBER} --timeout=300s -n ${KUBERNETES_NAMESPACE} || true
                        # Check job status
                        kubectl logs job/database-migrations-${BUILD_NUMBER} -n ${KUBERNETES_NAMESPACE}
                    '''
                }
            }
        }
    }

    post {
        always {
            // Clean up resources
            sh 'docker rmi ${DOCKER_IMAGE}:${BUILD_NUMBER} ${DOCKER_IMAGE}:latest || true'
        }
        success {
            echo 'Pipeline completed successfully!'
        }
        failure {
            echo 'Pipeline failed!'
        }
    }
}
