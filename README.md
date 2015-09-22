# Random Wallpaper
This script (still in the very early stages) will pick a (random) wallpaper
from [wallhaven](http://alpha.wallhaven.cc/). The script currently only supports
`feh` to set a background image, because `feh` is what I use myself. It
shouldn't be too hard to add support for let's say Gnome, but I can't test this
myself.

## Installation
Enter the directory of the script and run `composer install`. This will install
the dependencies (ivkos/wallhaven). After installation you can run the script
using `php wallhaven.php`. It is possible to set several options within the
script.

## Configuration
You can set your account details, so you can set NSFW wallpapers if you like.
Next you can change the default values. You can override those defaults based on
the time of the day. This is possible in the time section of the settings. Like
this it is possible to have SFW wallpapers during office hours and allow SKETCHY
or NSFW wallpapers outside office hours. For example you can set SFW landscapes
from 06:00-19:00 and allow SKETCHY wallpapers from 19:00-22:00. After 22:00 you
can allow NSFW wallpapers as well. Of course you can do whatever you like.
Another option is to have landscapes in the morning and cars in the afternoon.

Next you can set the download path. This is the path the script will use to
download the images. Afterwards those images are removed again. Maybe later I
will add an option to not remove the images. If
[wallhaven](http://alpha.wallhaven.cc) is unreachable a fallback is used. This
is a folder with images you would like to use when the connection fails.

# Example systemd service & timer files
```
[Unit]
Description=Set a random wallpaper.

[Service]
Type=oneshot
ExecStart=/usr/bin/php /path/to/randomWallpaper.php
```

```
[Unit]
Description=Run the randomwallpaper service every 30 minutes.

[Timer]
OnBootSec=1min
OnUnitActiveSec=30min

[Install]
WantedBy=timers.target
```

You can put those files in `~/.config/systemd/user/` and name them for example:
`randomwallpaper.service` and `randomwallpaper.timer`. Afterwards enable the
timer using `systemctl --user enable randomwallpaper.timer`

## License
The MIT License (MIT)

Copyright (c) 2015 Bart Kerkvliet <bkerkvliet@gmail.com>

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
