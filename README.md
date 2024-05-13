# WebHosting Automático

Se trata una aplicación web y un servidor FTP para hacer webhosting automático. Mediante Ubuntu, Nginx y vsftpd.

## Índice

- [Instalación](#instalación)
- [Configuración](#configuración)
  - [Nginx](#nginx)
  - [PHP-FPM](#php-fpm)
  - [VSFTPD](#vsftpd)
  - [SSH](#ssh)
  - [Reboot](#reboot)

## Instalación

1. Primero debemos de instalar los paquetes necesarios. Tienes una lista de ellos en [pkglist.txt](https://github.com/TeRacksito/WebHosting/blob/main/pkglist.txt). Puedes instalarlos todos a la vez con:

```bash
  sudo apt install $(cat pkglist.txt) -y
```

2. Luego, copia los archivos de no_comentado y pegalos en el directorio raíz de tu servidor web (_"/var/www/html", por defecto_).

```bash
  sudo cp -R /ruta/a/no_comentado/* /var/www/html/.
```

## Configuración

Ahora que tenemos todo listo, podemos empezar a configurar nuestro webhosting automático.

| :warning: WARNING                                            |
| :----------------------------------------------------------- |
| ¡Recuerda que esta configuración solo es válida para Ubuntu! |

### Nginx

Primero vamos a configurar el servidor web.

Si no se ha encendido ninguna vez (no ha generado los archivos de configuración) podemos hacer:

```bash
  systemctl start nginx
```

Esperar unos segundos y:

```bash
  systemctl stop nginx
```

Puedes hacer esto para los otros packages si no encuentras las configuraciones mencionadas.

1. #### `/etc/nginx/nginx.conf`

En este archivo solo queremos cambiar el usuario que usará nginx para sus procesos workers. El atributo se encuentra normalmente en la primera línea del archivo. Deberemos cambiarlo a:

```conf
  user=root;
```

2. #### `/etc/nginx/sites-available/default`

Necesitamos añadir nuestra página php a la configuración. Hay muchas opciones aqui, pero la configuración mínima y recomendada es:

```conf
server {
        listen 80 default_server;
        listen [::]:80 default_server;

        root /var/www/html;

        index index.html;

        server_name _;
        location /newuser.php {
                include snippets/fastcgi-php.conf;
                fastcgi_pass unix:/run/php/php8.1-fpm.sock;
        }

        location / {
                try_files $uri $uri/ =404;
        }
}
```

### PHP-FPM

Ahora configuramos el FPM de PHP.

1. #### `/etc/php/8.1/fpm/pool.d/www.conf`

Esta es el pool por defecto de fpm. De nuevo, necesitamos cambiar el usuario del pool de fpm para que pueda ejecutar comandos de adminsitrador.

Primero cambia el usuario y el grupo a root:

```conf
  ...
  user = root
  group = root
  ...
```

Luego haz lo mismo para estos dos:

```conf
  ...
  listen.owner = root
  listen.group = root
  ...
```

2. #### `/lib/systemd/system/php8.1-fpm.service`

Para que PHP-FPM pueda ejecutarse como root, necesitamos permitirselo. Es decir, iniciarlo con la opción -R. Para ello modificamos el atributo **ExecStart** en el apartado **Service** añadiendole una -R al final:

```service
  ExecStart=/usr/sbin/php-fpm8.1 --nodaemonize --fpm-config /etc/php/8.1/fpm/php-fpm.conf -R
```

### VSFTPD

Vamos a confiugrar el servidor FTP.

1. #### `/etc/vsftpd.conf`

Cambia los siguientes atributos. Te pongo `...` para que entiendas más o menos como están dispuestos, por defecto, estos atributos.

```conf
  anonymous_enable=NO
  local_enable=YES
  write_enable=YES
  ...
  chroot_local_user=YES
  chroot_list_enable=YES
  allow_writeable_chroot=YES
  chroot_list_file=/etc/vsftpd.chroot_list
  ...
  ssl_enable=YES
```

`chroot_local_user` dicta que los usuarios locales se verán encerrados en su directorio home cuando usan el servidor FTP.

2. #### Crea el directorio /etc/vsftpd.chroot_list

Es probable que no existe, crealo mediante:

```bash
  mkdir -p /etc/vsftpd.chroot_list
```

Dentro, añade el nombre de los usuarios que NO quieres que se vean afectados por el chroot.
Por ejemplo:

```txt
  root
  cuenta_importante
  ...
```

### SSH

Si tienes un servidor SSH, es importante hacer la siguiente configuración:

1. #### Crear grupo de ususarios `webuser`

Primero crearemos el grupo `webuser` para todos los registrados.

```bash
  groupadd webuser
```

| :warning: WARNING                                                     |
| :-------------------------------------------------------------------- |
| ¡Este usuario es de obligada creación, teniendo o no un servidor SSH! |

2. #### `/etc/ssh/sshd_config`

Luego, denegamos el grupo `webuser` al acceso SSH. Simplemente hay que añadir el siguiente atributo en cualquier línea del archivo:

```txt
  DenyGroups webuser
```

### Reboot

Una vez tengas todo configurado, reinicia el equipo y comprueba que todo funciona correctamente.

```bash
  reboot
```
