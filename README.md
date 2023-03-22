
# Tubeyou

- [Tubeyou](#tubeyou)
    - [features to implement](#features-to-implement)
    - [things to do](#things-to-do)
    - [setup instructions](#setup-instructions)


### features to implement

1. Videos
   1. ~~Upload video~~
   2. ~~Watch video~~
   3. Edit video (title, description, tags)
   4. Delete video
   5. Add content tags
   6. Add own thumbnail
   7. Edit thumbnail
   8. Count views
   9. Add liking and disliking
   10. Create multiple resolution video sources
   11. Create multiple resolution thumbnails
   12. Add pagination
2. Playlists
   1. Create playlist
   2. Add video to playlist
   3. Remove video from playlist
3. Channels
   1. View user videos and playlists
4. Comments
   1. User can write comments under videos
   2. Comments can have 1 level nested comments (replies).
   3. Tag other users.
5. Subscriptions
   1. User can subscribe to channel (and unsubscribe).
6. Design
   1. Custom page icon
   2. Style the navigation bar
   3. ~~Add sidebar for playlists, subscribed channels and other stuff~~
   4. Homepage
      1. ~~List public videos~~
      2. Search
7. Filesystem
   1. Deleted video files should be deleted after some time

### things to do

1. Style video upload progress
2. Let unregistered users view videos
3. Group vue templates by usage (videos, input)
4. ~~Show channel name and picture in video preview~~
5. Use Provide / Inject in the frontend

### setup instructions

1. copy .env.example to .env
2. configure .env file (set database file path)
3. ``` composer update ```
4. ``` npm install ```
5. ``` php artisan migrate ```
6. ``` php artisan key:generate ```
7. ``` php artisan storage:link ```
8. install ffmpeg (make sure you can run it from any path)
