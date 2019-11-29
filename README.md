# websecurity
## environment
- docker toolbox
- windows 10
- powershell
## possible bug
websecurity_webapp_1 exited  
fix: manually regenerate run_windows shell script
for example:  
> cat run_windows  
> \# copy content manually  
> rm run_windows  
> vim run_windows  
> \# paste content  
## usage
### build
> workdir=$(your workspace) # suggest to choice c:\\users\${your user name}\  
> workdir/>git clone git@github.com:qzwsq/websecurity.git  
> workdir/>git checkout windows  
> workdir/>docker-compose up -d  

view  
> workdir/>docker-machine ip default #get virtul machine ip => ${host}  
${host}:7001/index.php or ${host}:7002/index.php<br>

### exec container 
> workdir/>docker exec -it websecurity_webapp_1 bash

### trace php server log
> root@603bf93113ba:/home/workspace# tail -f /var/log/apache2/error.log 

### default account
username: admin password: 123456  

### next step
configure https 

