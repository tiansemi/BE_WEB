#!/bin/bash

: "
steps : 

$ mkdir webfonts && cd webfonts
$ chmod +x download.sh
$ ./autodownload.sh

NB : Ce script lit le fichier requirements.txt ligne par ligne, et chaque ligne est considérée comme le nom d’un fichier à télécharger. Assurez-vous que le fichier requirements.txt est dans le même répertoire que le script, ou modifiez le chemin du fichier en conséquence
"

# URL de base
base_url="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/webfonts/"

# Lire le fichier requirements.txt
while IFS= read -r file
do
  # Construction de l'URL du fichier
  file_url="${base_url}${file}"
  
  # Téléchargement du fichier
  wget $file_url
done < requirements.txt
