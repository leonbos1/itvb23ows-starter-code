pipeline {
    agent any
    stages {
        stage ('Build') {
            steps {
                echo 'Building...'
                sh 'sudo docker-compose build'
            }
        }

        stage ('Deploy') {
            steps {
                echo 'Deploying...'
                sh 'sudo docker-compose up -d'
            }
        }
    }
}