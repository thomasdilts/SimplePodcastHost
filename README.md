# SimplePodcastHost
This is a simple php podcast host that fulfills all the needs for podcasting and uses NO database. Simplicity is very important
if you decide to specialize the code for your specific use. Most programmers give up at the prospect of changing a very complex program.
The only difference between this podcasting host and the many other hosts that cost a lot of money is:

1. Analytics. No traffic analysis in this project. However you should have some sort of website analysis on your server anyway that can do the traffic analysis job. 
2. Advertising/apps.  You don't get any special advertising or apps with your podcast in this server. However, All the pages in this program are mobile phone friendly so it works in all devices. For advertising, you should register your rss feed with itunes anyway and there you get all the advertising you need. That is your feed will be searchable in almost all podcast apps.

# What you get

1. An rss feed that is an xml formatted file that is submitable to itunes for podcasts. Example: http://pingsteskilstunaweb.se/podcast/rss.php
2. An all devices friendly webpage that has an index of all your podcasts. Example: http://pingsteskilstunaweb.se/podcast/
3. An all devices friendly webpage that shows just one specific podcast at the top and then the list of all the other podcasts. This is very important for publishing just one podcast to websites like Facebook. Example: http://pingsteskilstunaweb.se/podcast/viewpodcast.php?podcast=190908
4. An upload php file that allows you to upload files to the server with a script. Included is a powershell script that you
can put on your windows desktop to do the uploading. For many this will probably not be so useful but for me I use it everytime.

# Requirements
Since this code is written to be as simple as possible, there are a few perhaps irritating requirements to fulfill:

1. You must have a sound file ending with '.mp3' and an image file ending with '.jpg' 
where the rest of the sound file name and image name are identical. For instance 'abc.mp3' and 'abc.jpg'.
These files must be in the subdirectory 'upload'.
This script will not work if you don't fullfill this requirement and inlcude both files.
2. You should have a 'title' inside of your sound mp3 file. If you don't then the title will be the name of the file.
You can use the free program ffmpeg and the parameter -metadata title="Sound Track Title"  to add a title to 
your sound mp3 file. 
3. This will sort the files descending by file name so it is recommended that you have a sortable date as the file name. For instance 
20191221.mp3 and 20191221.jpg.  Or perhaps 191221.mp3 and 191221.jpg. Otherwise you need to go into the code and change the sorting to something else. For instance, file-date could be something good to sort on instead.

# Installation

## Zip file installation

This is probably the easiest way to install for most people.

1. Download the latest version zip file found at https://github.com/thomasdilts/SimplePodcastHost/archive/master.zip 
2. Install all the files found in the zip file onto your server.

## Composer installation

If you wish to install with Composer then the command is:

```txt
composer create-project thomasdilts/simplepodcasthost
```

## Post installation

After doing one of the installs, this will work immediately with 2 sample files that are already in the "upload" directory.
You will probably want to change the file "fav.ico" because it is not what most people want for an icon. At the top of each php file is a list of varibles that you will want to change the values to.

# Special thanks to

This project has heavily borrowed from other projects. Particularly:

1. https://github.com/JamesHeinrich/getID3
2. https://github.com/aaronsnoswell/itunes-podcast-feed
