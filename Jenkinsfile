pipeline {
    agent {
        label 'docker-agent'
    }
    stages {
        stage ('Build') {
            steps {
                sh 'sudo docker-compose up -d'
            }
        }
    }
}