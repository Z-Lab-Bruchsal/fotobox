#!/usr/bin/bash
export LANGUAGE=

source /home/alegab/workspace/comfy_ui/venv/bin/activate

cp "$1" /home/alegab/comfy/ComfyUI/input/fotobox.jpg

echo "input: $1" >> image_log.txt

output=$(comfy-cli run --workflow /home/alegab/comfy/ComfyUI/user/default/workflows/comic2.json | grep "Outputs" -A 2 | tail -n 2 | head -n 1 | strings)
output=$(tr -dc '[[:print:]]' <<< "$output" | cut -c 5-38,43-)

echo "output: $output" >> image_log.txt

cp $output "$1"
