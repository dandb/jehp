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

Mixing group names will not affect the order of displayed groups. So...
```
qaEnv[]=qa_job1
qaEnv[]=qa_job2
teamCashCow[]=this_makes_bank_build1
qaEnv[]=qa_job3
teamCashCow[]=this_makes_less_but_still_lots_build3
```
...will still result in a clean output.

Order of Status Operation
-------------------------
```
Passed < Running Passed Previously
Running Passed Previously < Not Built, Disabled
Not Built, Disabled < Aborted
Aborted < Running Aborted Previously
Running Aborted Previously < Unstable
Unstable <  Running Failed Previously 
Running Failed Previously < Fail
```

Jenkins has 3 basic status returns. Those are blue (passed), aborted, and red (failed). Each of these return with "_anim" if the job is currently executing.
Running jobs always take precedence in status, with the exception of failures, for group health. The reason for this is potential for change. If all of your jobs have passed but one is currently running you may have an aborted or failed job in the near future. If all of your jobs are failed but one is running, the rest of your jobs will still be in a failed state when it concludes.

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


