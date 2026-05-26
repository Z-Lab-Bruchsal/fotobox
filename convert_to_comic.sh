#!/bin/sh
source /home/alegab/workspace/comfy_ui/venv/bin/activate

cp "$1" /home/alegab/comfy/ComfyUI/input/fotobox.jpg

#output=$(comfy-cli run --workflow /home/alegab/comfy/ComfyUI/user/default/workflows/comic2.json)
output=$(comfy-cli run --workflow /home/alegab/comfy/ComfyUI/user/default/workflows/comic2.json | grep "Outputs" -A 2 | tail -n 1)

cp $output "$1"

# cp storage/app/public/photos/006041bc-1065-4a4f-8f43-16d3be01f4db.jpeg /home/alegab/comfy/ComfyUI/input/fotobox.jpg