C'est vraiment juste un exercice de style pour utiliser quelques fonctions dont j'ai peu l'habitude, comme le type hinting à la sauce PHP, ou l'utilisation (correcte) de composer et des namespace.

Le nom HiTy provient évidemment de Type Hinting.

Le but, dans un premier temps, est d'automatiser la création de la base de données SQL à partir de classes très simples, où les types des champs sont issus des types PHP.

Dans un second temps, il faudrait automatiser et clarifier la gestion des migrations (elles sont claires dans Doctrine et Eloquent, mais trop verbeuses dans Eloquent, et dans Doctrine, ce sont les modèles eux-mêmes qui sont lourdingues à cause du pattern DataMapper).

# USAGE

Pour l'instant il faut aller spécifier le répertoire des modèles dans  
SyncCommand.php :  
private $modelFolder = 'src/StudioAPI/Model';  
private $modelNamespace = '\\StudioAPI\\Model';  

Les fichiers respectant ce folder et ce namespace peuvent contenir plusieurs classes à la fois (même si ça me paraît bordélique comme façon de ranger ses modèles).
```php
class MonModel {  
    // facultatif, à défaut, on utilisera le nom de la classe  
    private $name = 'ma_table';  
}
```

