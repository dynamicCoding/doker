# doker
crea tu aplicacion mvc con doker, capaz de crear una aplicacion facilmente
los controladores se cargar a traves de la url, puedes crear controladores con la linea de comando doker
deniega rutas una vez que el usuario se haya autenticado.

###inicializacion de doker
el archivo principal a la cual recibe las peticiones se encuentra en el directorio public/index.php
en el index se encuentra ya iniciazada la aplicacion.

la clase Bootstrap recibe como parametros primero el nombre del controlador y segundo el metodo por default. 

`new Doker(new Bootstrap("Index", "home"), $handler);`

###usando template
doker implementa una sencilla template utilizando la extencion .redis.php en el existe varias funcionalidades, al cargar el controlador puedes usar:
`$this->load->view('index')->vars(['title' => 'mi titulo'])` se buscara un archivo llamado index.php o index.redis.php si solo se desa cargar la vista sin usar el metodo vars
se debera de utilizar asi `$this->load->view('index', true)` al usar el template redis puedes utilizar estas funcionalidades como:
- imprimir valores con las etiquetas *{! $varible !}*
- evita la salida del codigo *{% $variable = 'string' %}*
- etiquetas para los comentarios *{# este es un comentario #}*
- creando salida de bloques con yield *@yield('content');*
- los bloques se crea con *@block('content');* 
- extendiendo de otro archivo con *@extend('filename');* si el archivo se encutra en otro directorio se lo especifica de esta manera or *@extend('dir.filename')*

- cargado imagenes *image_tag('image.png')*
- geneta un link especificando el href y el nombre el tercer parametro es opcional y es un array especificando la clave y el valor *link_to('/home', 'mi link', ['class' => 'myclass'])*
- genera un los distintos tipos de input *input('submit', 'name_input_submit', [opcional])*
- carga los archivos css espcificando el nombre si se lo deja vacio cargara todos los archivos css existentes en el directorio public/assets/css *link_tag_css('')*
- carga los script especificando los mismo valores que link_tag_css *script_tag()*
todas las funciones que utilizen usando el template redis deben de hacer con la etiquetas de salida *{! script_tag('script') !}*
las funciones script_tag y link_tag_css no se debe especificar solo el nombre del archivo

>nota: la documentacion pronto estara disponible.