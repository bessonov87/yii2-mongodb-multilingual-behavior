Yii2 multilingual behavior
==========================
Yii2 MongoDb port of the [yii2-multilingual-behavior](https://github.com/OmgDef/yii2-multilingual-behavior).

[![Packagist Version](https://img.shields.io/packagist/v/omgdef/yii2-multilingual-behavior.svg?style=flat-square)](https://packagist.org/packages/omgdef/yii2-multilingual-behavior)
[![Total Downloads](https://img.shields.io/packagist/dt/omgdef/yii2-multilingual-behavior.svg?style=flat-square)](https://packagist.org/packages/omgdef/yii2-multilingual-behavior)
[![Build Status](https://img.shields.io/travis/OmgDef/yii2-multilingual-behavior/master.svg?style=flat-square)](https://travis-ci.org/OmgDef/yii2-multilingual-behavior)
[![Code Quality](https://img.shields.io/scrutinizer/g/omgdef/yii2-multilingual-behavior/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/OmgDef/yii2-multilingual-behavior)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/omgdef/yii2-multilingual-behavior/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/OmgDef/yii2-multilingual-behavior)

This behavior allows you to create multilingual models and almost use them as normal models. Translations are stored in a separate table in the database (ex: PostLang or NewsLang) for each model, so you can add or remove a language easily, without modifying your database.

Example
-------

If you use `multilingual()` in a `find()` query, every model translation is loaded as virtual attributes (title_en, title_fr, title_de, ...).

```php
$model = Post::find()->multilingual()->one();
echo $model->title_en; //echo "English title"
echo $model->title_fr; //echo "Titre en Français"
```

Installation
------------

Preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist bessonov87/yii2-mongodb-multilingual-behavior
```

or add

```
"bessonov87/yii2-mongodb-multilingual-behavior": "*"
```

to the require section of your `composer.json` file.

Behavior attributes
------------
Attributes marked as bold are required

Attribute | Description
----------|------------
languageField | The name of the language field of the translation table. Default is 'language'
localizedPrefix | The prefix of the localized attributes in the lang table. Is used to avoid collisions in queries. The columns in the translation table corresponding to the localized attributes have to be name like this: ```[prefix]_[name of the attribute]``` and the id column (primary key) like this : ```[prefix]_id```
requireTranslations | If this property is set to true required validators will be applied to all translation models.
dynamicLangClass | Dynamically create translation model class. If true, the translation model class will be generated on runtime with the use of the eval() function so no additionnal php file is needed
langClassName | The name of translation model class. Dafault value is model name + Lang
**languages** | Available languages. It can be a simple array: ```['fr', 'en']``` or an associative array: ```['fr' => 'Français', 'en' => 'English']```
**defaultLanguage** | The default language
**langForeignKey** | Name of the foreign key field of the translation table related to base model table.
**tableName** | The name of the translation table
**attributes** | Multilingual attributes

Usage
-----

Here an example of base 'news' table:

Attaching this behavior to the model (News in the example). Commented fields have default values.

```php
public function behaviors()
{
    return [
        'ml' => [
            'class' => MultilingualBehavior::className(),
            'languages' => [
                'en-US' => 'English',
		'de' => 'German',
            ],
            //'languageField' => 'language',
            //'localizedPrefix' => '',
            //'requireTranslations' => false',
            //'dynamicLangClass' => true',
            //'langClassName' => PostLang::className(), // or namespace/for/a/class/PostLang
            'defaultLanguage' => 'en',
            'langForeignKey' => 'post_id',
            'tableName' => "newsLang",
            'attributes' => [
                'title', 'text',
            ]
        ],
    ];
}
```

Then you have to overwrite the `find()` method in your model

```php
    public static function find()
    {
        return new MultilingualQuery(get_called_class());
    }
```

As this behavior has ```MultilingualTrait```, you can use it in your query classes

```php
namespace app\models;

use yii\mongodb\ActiveQuery;

class MultilingualQuery extends ActiveQuery
{
    use MultilingualTrait;
}
```

Form example:
```php
//title will be saved to model table and as translation for default language
$form->field($model, 'title')->textInput(['maxlength' => 255]);
$form->field($model, 'title_en')->textInput(['maxlength' => 255]);
```
