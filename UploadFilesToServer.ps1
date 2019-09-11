#
# This is a windows powershell program to convert an mp4 to mp3 and upload an mp3 file and a jpg file
# to your podcast server via the upload.php file also in this project. This file MUST be changed in many 
# places by you before using. You may need to cut out large bits of code here that you don't need.
#
write-host "Hi! This will create an mp3 file from an mp4 file and move the file to its final destination."
write-host
write-host "You need to input 6 digits and then hit enter."
write-host "The six digits will look something like '191004'."
write-host "These six digits are the name of the mp4 file in 'f:\mp4 out'."
write-host
write-host "You must also have a graphics file (.jpg) in the same directory with the same name."
write-host
write-host
$filename = Read-Host -Prompt 'Input the 6 digit name of the mp4 file'

#
# you need to change the directory here to where your files are located
#
$mp4File="f:\mp4 out\$filename.mp4"
if(![System.IO.File]::Exists($mp4File)){
    write-host "The file '$mp4File' does not exist. Restart the program"
    pause
    return
}
#
# you need to change the directory here to where your files are located
#
$jpgFile="f:\mp4 out\$filename.jpg"
if(![System.IO.File]::Exists($jpgFile)){
    write-host "The file '$jpgFile' does not exist. Restart the program"
    pause
    return
}

$title = Read-Host -Prompt 'Enter the title of the video'
#
# Need to change the output file path
#
$arguments="-ss 00:00:22 -i """ + $mp4File + """  -metadata title=""" + $title + """ -ac 1 ""f:\mp3file\" + $filename +".mp3"""

#
# This uses ffmpeg (free open source program) to convert an mp4 to mp3 file and add a title.
# Improvements to this would be to also add the 'artist.' You might not need this conversion at 
# all but you can also use ffmpeg to just add the Title and Artist if you want to an mp3 file.
#

$exitCode = [Diagnostics.Process]::Start("C:\Program Files\ffmpeg\bin\ffmpeg.exe",$arguments).WaitForExit()

#
# Here is where the .jpg and the .mp3 file is uploaded onto the server.
# This might be all you need to do.
# Future improvements here would be to put the authorization into the header instead.
# Then it would be secure in a https communication. As it is in the URL is no security at all.
#
# You need to change the URL(uri) to be your own personal URL to your server.
#

$userPassObj = @{
	username = 'PUT_YOUR_USERNAME_HERE'
	password = 'PUT_YOUR PASSWORD_HERE'
}
$json = ConvertTo-Json $userPassObj
$auth=[Convert]::ToBase64String([System.Text.Encoding]::ASCII.GetBytes($json));

$uri ="http://pingsteskilstunaweb.se/podcast/upload.php?filename=" + $filename + ".jpg&auth=" + $auth
$uploadPath=$jpgFile

Invoke-RestMethod -Uri $uri -Method Post -InFile $uploadPath 


$uri ="http://pingsteskilstunaweb.se/podcast/upload.php?filename=" + $filename + ".mp3&auth=" + $auth
$uploadPath="f:\mp3file\" + $filename +".mp3"

Invoke-RestMethod -Uri $uri -Method Post -InFile $uploadPath 

write-host
write-host
write-host "Congradulations. All files sucessfully moved. Thanks!"
pause