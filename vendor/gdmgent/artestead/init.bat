@echo off

if ["%~1"]==["json"] (
    copy /-y resources\Artestead.json Artestead.json
)
if ["%~1"]==[""] (
    copy /-y resources\Artestead.yaml Artestead.yaml
)

copy /-y resources\after.sh after.sh
copy /-y resources\aliases.sh aliases.sh

echo Artestead initialized!
