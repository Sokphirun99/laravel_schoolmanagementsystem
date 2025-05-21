#!/bin/zsh

# Quick Start Guide for Setting Up CI/CD with Jenkins and Kubernetes
echo "=== School Management System CI/CD Setup ==="
echo ""
echo "This script will help you set up the CI/CD pipeline for the School Management System."
echo ""

# Check prerequisites
echo "== Checking prerequisites =="
command -v docker >/dev/null 2>&1 || { echo "Docker is required but not installed. Aborting."; exit 1; }
command -v kubectl >/dev/null 2>&1 || { echo "kubectl is required but not installed. Aborting."; exit 1; }
echo "✅ Prerequisites checked"
echo ""

# Repository setup
echo "== Repository Setup =="
echo "1. Push your code to a Git repository (GitHub, GitLab, etc.)"
echo "   git push origin master"
echo ""

# Jenkins setup
echo "== Jenkins Setup =="
echo "1. Install Jenkins or use an existing Jenkins instance"
echo "2. Install required Jenkins plugins:"
echo "   - Kubernetes plugin"
echo "   - Docker Pipeline plugin"
echo "   - Blue Ocean plugin"
echo "   - Credentials Binding plugin"
echo "3. Configure Jenkins credentials:"
echo "   - Docker registry credentials (ID: docker-registry-credentials)"
echo "   - Kubernetes configuration (ID: kubeconfig)"
echo "4. Create a new Jenkins Pipeline job pointing to your repository"
echo "   - Set the Script Path to 'Jenkinsfile'"
echo ""

# Docker registry setup
echo "== Docker Registry Setup =="
echo "1. Make sure you have access to a Docker registry"
echo "2. Update the Jenkinsfile with your registry information:"
echo "   - Edit DOCKER_REGISTRY in the environment section"
echo "3. Ensure Jenkins has credentials to push to this registry"
echo ""

# Kubernetes setup
echo "== Kubernetes Setup =="
echo "1. Make sure you have a Kubernetes cluster running"
echo "2. Update Kubernetes configuration files in the kubernetes/ directory:"
echo "   - Edit registry references in deployment.yaml"
echo "   - Update hostnames in ingress.yaml"
echo "   - Set appropriate storage class in storage.yaml"
echo "3. Apply Kubernetes namespace:"
echo "   kubectl apply -f kubernetes/namespace.yaml"
echo "4. Apply Kubernetes configurations:"
echo "   ./kubernetes/setup.sh"
echo ""

# First pipeline run
echo "== Running the CI/CD Pipeline =="
echo "1. In Jenkins, navigate to your pipeline job"
echo "2. Click 'Build Now' to start the pipeline"
echo "3. Monitor the pipeline execution in the Jenkins UI"
echo ""

echo "=== Setup Complete ==="
echo "For more detailed instructions, refer to the Jenkins and Kubernetes setup guide:"
echo "docs/jenkins-kubernetes-setup.md"
