# Symfony test project

## Docker network configuration

Create network from dedicated local ip
* If the network uses this ip then select others (eg 172.255.0.1 or similar)
``` 
docker network create nginx-proxy --subnet=172.18.0.0/16 --gateway=172.18.0.1
```

Inspect network
``` 
docker network inspect nginx-proxy
```
Add to /ete/hosts IP gateway
* Also change the IP gateway in the .env file NGINX_PROXY_IP=172.18.0.1 
``` 
sudo echo "172.18.0.1 symfonytests.lh" >> /etc/hosts
```

## First run
1. Copy .env.disc to **.env** in **_develop** folder.
2. Copy .env to **.env.local** in **symfony** folder.
```cp .env .env.local```
3. Update **MERCURE_PUBLISH_URL** and **MERCURE_JWT_SECRET**.  
Remember to have the same values in .env and in jwt.io key generator.

4. ```composer install```

## Testing

1. Open http://symfonytests.lh/test/receive/1 for authenticated subscriber.
2. Open second window/new tab http://symfonytests.lh/test/receive
3. Now open in the new tab http://symfonytests.lh/test/publish to sending new message.
5. Look at the first window for response and no messages at the second tab.

## Generate new JWT Key:

You must change **your-256-bit-secret** to your secret provided in **_develop/.env**

[https://jwt.io/](https://jwt.io/#debugger-io?token=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJtZXJjdXJlIjp7InB1Ymxpc2giOltdfX0.473isprbLWLjXmAaVZj6FIVkCdjn37SQpGjzWws-xa0)

