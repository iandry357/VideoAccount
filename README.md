# VideoAccount
Context : plateform where user can add video or interact with video added by other

Task 1 : initialiser la base de données
=> Create database
=> Create table User (int id, varchar name, varchar pwd)
=> Create table Video (int id, varchar name, varchar link, int id_user )
=> Create table UserVideoAction (int id, int id_user, int id_video, int typeAction, varchar value)

Task 2 : formulaire inscription/connexion
=> name, mdp

Task 3 : ajouter des vidéos
=> name, link
=> initialiser nombre like/dislike + commentaire

Task 4 : intéragir avec les vidéos envoyées par les autres
