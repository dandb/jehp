jehp
====

Jenkins Environment Health Page

Simple Php page for checking team, environment, or other group build health in Jenkins at a glance.
The entire intent of this project is to very quickly answer questions like "what's the status of [your build] in qa/stg?"

At initial commit, the config requires username, password, and the url for your Jenkins page.
The groups.ini requires groupings you define. For example, the array...
```
qaEnv[]=qa_job1
qaEnv[]=qa_job2
qaEnv[]=qa_job3
```
...will give you a single cell, colored for worst possible health of the three jobs in the array, labeled as qaEnv.


Mixing group names will not interfere with the groups created. The following example will still result in a clean output.
```
qaEnv[]=qa_job1
qaEnv[]=qa_job2
teamCashCow[]=this_makes_bank_build1
qaEnv[]=qa_job3
teamCashCow[]=this_makes_less_but_still_lots_build3
```
However, the groups.ini file is parsed sequentially and can affect the order of groupings displayed in JEHP.
In the above example, the qaEnv group is listed before the teamCashCow group.
Thus, JEHP will show two cells while using the above groups.ini, where the qaEnv group will appear as the first cell and the teamCashCow group will appear below it.


Job Status Ranking and Color Code
---------------------------------
```
|Ranking of Job Status From Best To Worst |  Color  |
|---------------------------------------- | ------- |
|1. Passed                                |  Green  | 
|2. Not Built, Disabled, Aborted, Unstable|  Yellow |
|3. Failed                                |  Red    |
```

Jenkins has 3 basic colors to represent build status: Blue (Passed), Gray (Aborted), and Red (Failed). If the job is currently executing, each status returns with "*_anime".
In JEHP, there will also be 3 main colors, Green, Yellow, and Red, to indicate the health of a group of builds. The cells, which represent the health of a build group, will be the color of the worst ranking job in its group. Additionally, if there is a running job within its group, the cell will blink to indicate this.

If you see a orange cell appear that means a job in your config was not found in the xml Jenkins returned. The missing job name will be in the cell.

Configuration
-------------
Set your Jenkins username, API token, and url in the config file.
Add your build/job groupings to the groups.ini file (Php array formatting).

If you use apache I'd recommend
```
Order deny,allow
Deny from all
```
for all files except the quickHealth.html.

Cron reportBuilder.sh


