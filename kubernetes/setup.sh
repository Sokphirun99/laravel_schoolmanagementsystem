#!/bin/zsh

# This script sets up the Kubernetes resources for the school management system

# Set variables
NAMESPACE="school-management"
REGISTRY="docker.io/yourusername"  # Replace with your docker registry username
IMAGE_NAME="laravel-school-management"
IMAGE_TAG="latest"

# Create namespace
echo "Creating namespace..."
kubectl apply -f kubernetes/namespace.yaml

# Create secrets and configmap
echo "Creating ConfigMap and Secrets..."
kubectl apply -f kubernetes/config.yaml

# Create storage
echo "Creating storage resources..."
kubectl apply -f kubernetes/storage.yaml

# Create migrations job
echo "Creating migrations CronJob..."
kubectl apply -f kubernetes/migrations.yaml

# Create deployment
echo "Creating deployment..."
kubectl apply -f kubernetes/deployment.yaml

# Create service
echo "Creating service..."
kubectl apply -f kubernetes/service.yaml

# Create ingress
echo "Creating ingress..."
kubectl apply -f kubernetes/ingress.yaml

echo "Kubernetes setup completed!"
echo "You can check the status of your deployment using:"
echo "kubectl get all -n $NAMESPACE"
