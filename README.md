# Joole Component: Renderer

This component is an implementation of the html content renderer.
## Getting started

* Install this dependency via composer: <code>composer install zloynick/joole-components-renderer</code>

## Configuration

Add to components this in your joole.php configuration file:
<pre>
<code>
'components' => [
        ...,
        [
            'name' => 'renderer',
            'class' => \joole\components\renderer\RendererComponent::class,
            // Component options
            'options' => [
                // Views path
                'views' => __DIR__.'/../views/',
            ],
        ],
        ...,
    ],
</code>
</pre>

## Using

**Views**

Create your first view file:

<pre>
----------index.php-----------
<code>
...
/** @var array $rating */

foreach($rating as $place => $name){
...
}
...
</code>
</pre>

**Rendering with params**

Every param name is variable, that can be used in view file.

<pre>
<code>
$viewObject = \joole\framework\Joole::$app->getRenderer()->renderView('index.php', [
    'rating' => ['Anastasia', 'Mikhail', 'Artem'],
]);
</code>
</pre>


**JS/CSS rendering**

You can also add CSS/JS content to view:

<pre>
<code>
...
$viewObject->renderJs(' console.log("Hello, world!")) ');
$viewObject->renderCss('body{ background-color: #c0c0c0 }');
...
</code>
</pre>