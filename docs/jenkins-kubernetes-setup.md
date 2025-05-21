# Setting Up CI/CD with Jenkins and Kubernetes

This guide explains how to set up and use the Jenkins pipeline with Kubernetes for automated build, test, and deployment of the Laravel School Management System.

## Prerequisites

- Jenkins server with the following plugins installed:
  - Kubernetes plugin
  - Docker Pipeline plugin
  - Blue Ocean plugin (for improved UI)
  - Credentials Binding plugin
- Access to a Kubernetes cluster
- Docker registry (e.g., Docker Hub, AWS ECR, GitLab Container Registry)

## Jenkins Setup

### 1. Configure Jenkins Kubernetes Cloud

1. Go to Jenkins > Manage Jenkins > Manage Nodes and Clouds > Configure Clouds
2. Add a new cloud > Kubernetes
3. Configure the following settings:
   - Name: kubernetes
   - Kubernetes URL: Your Kubernetes API server URL
   - Kubernetes Namespace: jenkins
   - Jenkins URL: Your Jenkins URL
   - Jenkins tunnel: Your Jenkins tunnel for agent connection
4. Click "Test Connection" to verify the configuration
5. Configure pod templates if needed

### 2. Configure Jenkins Credentials

Add the following credentials in Jenkins > Manage Jenkins > Manage Credentials:

1. **Docker Registry Credentials:**
   - Kind: Username with password
   - ID: docker-registry-credentials
   - Description: Docker registry access
   - Username: Your registry username
   - Password: Your registry password

2. **Kubernetes Config:**
   - Kind: Secret file
   - ID: kubeconfig
   - Description: Kubernetes configuration
   - File: Upload your kubeconfig file

### 3. Create a Jenkins Pipeline Job

1. Go to Jenkins > New Item
2. Enter a name (e.g., "Laravel School Management")
3. Select "Pipeline" as the job type
4. Click "OK"
5. In the configuration page:
   - Under "Pipeline", select "Pipeline script from SCM"
   - Select "Git" as the SCM
   - Enter your repository URL
   - Set the credentials for your Git repository
   - Specify the branch to build (e.g., */master)
   - Set the Script Path to "Jenkinsfile"
6. Click "Save"

## Using the Pipeline

### Starting a Build

1. Go to the pipeline job in Jenkins
2. Click "Build Now" to start a new build
3. The pipeline will run through all the stages defined in the Jenkinsfile

### Monitoring Builds

1. Click on a build number to see details
2. Click "Console Output" to see logs
3. Alternatively, use Blue Ocean for a more visual experience by clicking "Open in Blue Ocean"

### Deployment Stages

The pipeline includes the following stages:

1. **Checkout**: Retrieves the code from the repository
2. **Composer Install**: Installs PHP dependencies
3. **Run Tests**: Executes unit and feature tests
4. **Build Docker Image**: Creates a Docker image of the application
5. **Push Docker Image**: Pushes the image to the Docker registry
6. **Deploy to Kubernetes**: Updates the Kubernetes deployment with the new image
7. **Run Database Migrations**: Applies any database migrations

## Customizing the Pipeline

You can customize the Jenkins pipeline by editing the `Jenkinsfile` in your repository. Key areas to modify:

- Environment variables at the top of the file
- Docker image names and tags
- Kubernetes namespace and deployment names

## Kubernetes Resources

The application is deployed using the following Kubernetes resources:

- **Namespace**: Isolates the application resources
- **Deployment**: Manages the application pods
- **Service**: Exposes the application within the cluster
- **Ingress**: Routes external traffic to the service
- **ConfigMap & Secret**: Store configuration and sensitive data
- **PersistentVolumeClaim**: Provides persistent storage
- **CronJob**: Handles scheduled tasks and migrations

## Troubleshooting

### Common Issues

1. **Docker Build Failures**:
   - Check Dockerfile for errors
   - Ensure all dependencies are available

2. **Kubernetes Deployment Issues**:
   - Verify kubeconfig is correct
   - Check pod logs with `kubectl logs`
   - Examine events with `kubectl get events`

3. **Pipeline Permission Issues**:
   - Ensure Jenkins has proper permissions for the Kubernetes cluster
   - Verify Docker registry credentials

### Getting Help

For assistance with the CI/CD pipeline:
1. Check the Jenkins logs
2. Examine Kubernetes resources with `kubectl get all -n school-management`
3. Contact the DevOps team
