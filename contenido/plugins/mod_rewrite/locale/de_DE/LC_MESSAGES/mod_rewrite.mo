��    C      4  Y   L      �  s   �  l   %  {   �  j        y  @   �     �  0   �  8        V  8   ]  B   �  C   �  I   	  J   g	  :   �	     �	  E   
  E   K
  h   �
     �
  &   
  �  1     �     �     	  �     O   �  I   �  M   1  G        �  #   �     �  )     /   2  *   b  0   �     �     �      �     �       >   1  ?   p  F   �  M   �  9   E       �   �  T   7  S   �  �   �  P   �  ,     �   8       !     #   =  !   a     �     �     �  �   �     [  �   t  ,  M  x   z  r   �  �   f  {   �     c  I   }  !   �  7   �  F   !     h  ;   n  N   �  P   �  Q   J  V   �  J   �  -   >  k   l  F   �  �         �   %   �     �      �"     #     (#  �   1#  O   �#  O   '$  O   w$  O   �$     %  3   %  ,   S%  8   �%  -   �%  8   �%  -    &     N&     Z&      n&     �&     �&  >   �&  T   '  Z   c'  T   �'  8   (     L(  �   d(  _   3)  _   �)  �   �)  _   �*  /   K+    {+  �   �,  +   ~-  )   �-  "   �-     �-     .     .  �   ..  (   �.  �   	/     3                 A   %   ?   C         9            )   2             6          1   0      $      5      >                             B      	   +       *         :   8       @      '   /   .   (   "   #   4                           =      ;       <       !       -   &                 ,   7   
                          # Example: Category separator (/) and article-word separator (-)
category_one/category_two/article-description.html # Example: Category separator (/) and category-word separator (_)
category_one/category_two/articlename.html # Example: Category-article separator (/) and article-word separator (-)
category_one/category_two/article-description.html # enable apache mod rewrite module
RewriteEngine on

# disable apache mod rewrite module
RewriteEngine off (possible values: %s) Append article name allways to URLs (even at URLs to categories) Append article name to URLs Are several clients maintained in one directory? Article-word separator (delemiter between article words) Author Category separator (delemiter between single categories) Category separator has to be different from article-word separator Category separator has to be different from category-word separator Category-article separator (delemiter between category-block and article) Category-article separator has to be different from article-word separator Category-word separator (delemiter between category words) Check path to .htaccess Configuration could not saved. Please check write permissions for %s  Configuration has <b>not</b> been saved, because of enabled debugging Configure your own separators with following 4 settings<br />to control generated URLs to your own taste Contenido forum Default article name without extension Disabling of plugin does not result in disabling mod rewrite module of the web server - This means,<br /> all defined rules in the .htaccess are still active and could create unwanted side effects.<br /><br />Apache mod rewrite could be enabled/disabled by setting the RewriteEngine directive.<br />Any defined rewrite rules could remain in the .htaccess and they will not processed,<br />if the mod rewrite module is disabled E-Mail to author Enable Advanced Mod Rewrite Example If enabled, the name of the root category (e. g. "Mainnavigation" in a Contenido default installation), will be prepended to the URL. Invalid separator for article words, allowed is one of following characters: %s Invalid separator for article, allowed is one of following characters: %s Invalid separator for category words, allowed one of following characters: %s Invalid separator for category, allowed one of following characters: %s Note Path to .htaccess from DocumentRoot Please check your input Please specify separator (%s) for article Please specify separator (%s) for article words Please specify separator (%s) for category Please specify separator (%s) for category words Plugin page Plugin settings Plugin thread in Contenido forum Prepend client to the URL Prepend language to the URL Separator for category and article words must not be identical Separator for category and category words must not be identical Separator for category-article and article words must not be identical Should the language appear in the URL (required for multi language websites)? Should the name of root category be displayed in the URL? Start from root category The .htaccess file could not found either in Contenido installation directory nor in client directory.<br />It should set up in %sFunctions%s area, if needed. The article name has a invalid format, allowed are the chars /^[a-zA-Z0-9\-_\/\.]*$/ The file extension has a invalid format, allowed are the chars \.([a-zA-Z0-9\-_\/]) The path will be checked, if this option is enabled.<br />But this could result in an error in some cases, even if the specified path is valid and<br />clients DocumentRoot differs from Contenido backend DocumentRoot. The root directory has a invalid format, alowed are the chars [a-zA-Z0-9\-_\/\.] The specified directory "%s" does not exists The specified directory "%s" does not exists in DOCUMENT_ROOT "%s". this could happen, if clients DOCUMENT_ROOT differs from Contenido backends DOCUMENT_ROOT. However, the setting will be taken over because of disabled check. Type '/' if the .htaccess file lies inside the wwwroot (DocumentRoot) folder.<br />Type the path to the subfolder fromm wwwroot, if Contenido is installed in a subfolder within the wwwroot<br />(e. g. http://domain/mycontenido -&gt; path = '/mycontenido/') Use client name instead of the id Use language name instead of the id Value has to be between 0 an 100. Value has to be numeric. Version Visit plugin page e. g. "index" for index.ext<br />In case od selected "Append article name allways to URLs" option and a empty field,<br />the name of the start article will be used opens page in new window www.domain.com/category1-category2.articlename.html
www.domain.com/category1/category2-articlename.html
www.domain.com/category.name1~category2~articlename.html
www.domain.com/category_name1-category2-articlename.foo Project-Id-Version: Contenido Plugin Advanced Mod Rewrite
Report-Msgid-Bugs-To: 
POT-Creation-Date: 2011-04-15 00:56+0100
PO-Revision-Date: 2011-04-15 00:56+0100
Last-Translator: Murat Purc <murat@purc.de>
Language-Team: Murat Purc <murat@purc.de>
MIME-Version: 1.0
Content-Type: text/plain; charset=UTF-8
Content-Transfer-Encoding: 8bit
X-Poedit-Language: German
X-Poedit-Country: GERMANY
X-Poedit-Basepath: C:/dev/websites/contenido/src/
X-Poedit-KeywordsList: i18n
X-Poedit-SourceCharset: iso-8859-1
X-Poedit-SearchPath-0: contenido/plugins/mod_rewrite
 # Beispiel: Kategorie-Separator (/) und Artikelwort-Separator (-)
kategorie_eins/kategorie_zwei/artikel-bezeichnung.html # Beispiel: Kategorie-Separator (/) und Kategoriewort-Separator (_)
kategorie_eins/kategorie_zwei/artikelname.html # Beispiel: Kategorie-Artikel-Separator (/) und Artikelwort-Separator (-)
kategorie_eins/kategorie_zwei/artikel-bezeichnung.html # aktivieren des apache mod rewrite moduls
RewriteEngine on

# deaktivieren des apache mod rewrite moduls
RewriteEngine off (m&ouml;gliche Werte: %s) Artikelname immer an die URLs anh&auml;ngen (auch bei URLs zu Kategorien) Artikelname an URLs anh&auml;ngen Werden mehrere Mandanten in einem Verzeichnis gepflegt? Artikelwort-Separator (Trenner zwischen einzelnen Artikelw&ouml;rtern) Autor Kategorie-Separator (Trenner zwischen einzelnen Kategorien) Kategorie-Separator und Artikelwort-Separator m&uuml;ssen unterschiedlich sein Kategorie-Separator und Kategoriewort-Separator m&uuml;ssen unterschiedlich sein Kategorie-Artikel-Separator (Trenner zwischen Kategorieabschnitt und Artikelname) Kategorie-Artikel-Separator und Artikelwort-Separator m&uuml;ssen unterschiedlich sein Kategoriewort-Separator (Trenner zwischen einzelnen Kategoriew&ouml;rtern) Pfad zur .htaccess Datei &uuml;berpr&uuml;fen Konfiguration konnte nicht gespeichert werden. &Uuml;berpr&uuml;fen Sie bitte die Schreibrechte f&uuml;r %s Konfiguration wurde <b>nicht</b> gespeichert, weil debugging aktiv ist Mit den n&auml;chsten 4 Einstellungen k&ouml;nnen die Trennzeichen in den<br />generierten URLs nach den eigenen W&uuml;nschen gesetzt werden. Contenido Forum Standard-Artikelname ohne Dateiendung Beim Deaktivieren des Plugins wird das mod rewrite Modul des Webservers nicht mit deaktiviert - Das bedeutet, <br />dass alle in der .htaccess definerten Regeln weiterhin aktiv sind und einen unerw&uuml;nschten Nebeneffekt haben k&ouml;nnen.<br /><br />Apache mod rewrite l&auml;sst sich in der .htaccess durch das Setzen der RewriteEngine-Direktive aktivieren/deaktivieren.<br />Wird das mod rewrite Modul deaktiviert, k&ouml;nnen die in der .htaccess definierten Regeln weiterhin bleiben, sie werden <br />dann nicht verarbeitet. E-Mail an Autor Advanced Mod Rewrite aktivieren Beispiel Ist diese Option gew&auml;hlt, wird der Name des Hauptbaumes (Kategoriebaum, <br />z. B. "Hauptnavigation" bei Contenido Standardinstallation) der URL vorangestellt. Trenner f&uuml;r Kategorie ist ung&uuml;ltig, erlaubt ist eines der Zeichen: %s Trenner f&uuml;r Kategorie ist ung&uuml;ltig, erlaubt ist eines der Zeichen: %s Trenner f&uuml;r Kategorie ist ung&uuml;ltig, erlaubt ist eines der Zeichen: %s Trenner f&uuml;r Kategorie ist ung&uuml;ltig, erlaubt ist eines der Zeichen: %s Hinweis Pfad zur .htaccess Datei vom DocumentRoot ausgehend Bitte &uuml;berpr&uuml;fen Sie ihre Eingaben Bitte Trenner (%s) f&uuml;r Kategoriew&ouml;rter angeben Bitte Trenner (%s) f&uuml;r Kategorie angeben Bitte Trenner (%s) f&uuml;r Kategoriew&ouml;rter angeben Bitte Trenner (%s) f&uuml;r Kategorie angeben Pluginseite Plugineinstellungen Pluginbeitrag im Contenido Forum Mandant an die URL voranstellen Sprache an die URL voranstellen Separator for category and article words must not be identical Trenner f&uuml;r Kategorie und Kategoriew&ouml;rter d&uuml;rfen nicht identisch sein Trenner f&uuml;r Kategorie-Artikel und Artikelw&ouml;rter d&uuml;rfen nicht identisch sein Soll die Sprache mit in der URL erscheinen (f&uuml;r Mehrsprachsysteme unabdingbar)? Soll der Name des Hauptbaumes mit in der URL erscheinen? Start vom Hauptbaum aus Es wurde weder im Contenido Installationsverzeichnis noch im Mandantenverzeichnis eine .htaccess Datei gefunden.<br />Die .htaccess Datei sollte gegebenenfalls im Bereich %sFunktionen%s eingerichtet werden. Das Rootverzeichnis hat ein ung&uuml;ltiges Format, erlaubt sind die Zeichen [a-zA-Z0-9\-_\/\.] Das Rootverzeichnis hat ein ung&uuml;ltiges Format, erlaubt sind die Zeichen [a-zA-Z0-9\-_\/\.] Ist diese Option aktiv, wird der eingegebene Pfad &uuml;berpr&uuml;ft. Das kann unter <br />Umst&auml;nden einen Fehler verursachen, obwohl der Pfad zwar richtig ist, aber der Mandant <br />einen anderen DocumentRoot als das Contenido Backend hat. Das Rootverzeichnis hat ein ung&uuml;ltiges Format, erlaubt sind die Zeichen [a-zA-Z0-9\-_\/\.] Das angegebene Verzeichnis "%s" existiert nicht Das angegebene Verzeichnis "%s" existiert nicht im DOCUMENT_ROOT "%s". Das kann vorkommen, wenn das DOCUMENT_ROOT des Mandanten vom Contenido Backend DOCUMENT_ROOT abweicht. Die Einstellung wird dennoch &uuml;bernommen, da die &Uuml;berpr&uuml;fung abgeschaltet wurde. Liegt die .htaccess im wwwroot (DocumentRoot), ist '/' anzugeben, ist Contenido in einem <br />Unterverzeichnis von wwwroot installiert, ist der Pfad vom wwwroot aus anzugeben <br />(z. B. http://domain/mycontenido -&gt; Pfad = '/mycontenido/'). Name des Mandanten anstatt die Id verwenden Name der Sprache anstatt die Id verwenden Wert muss zwischen 0 und 100 sein. Wert muss numerisch sein. Version Pluginseite besuchen z. B. "index" f&uuml;r index.ext.<br />Wenn die Option "Artikelname immer an die URLs anhängen" aktiviert und das Feld leer ist,<br />wird der Name des Startartikels verwendet. &ouml;ffnet Seite in einem neuen Fenster www.domain.de/kategorie1-kategorie2.artikelname.html
www.domain.de/kategorie1/kategorie2-artikelname.html
www.domain.de/kategorie.name1~kategorie2~artikelname.html
www.domain.de/kategorie_name1-kategorie2-artikelname.foo 