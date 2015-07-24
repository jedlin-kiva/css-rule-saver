Kiva's fork of Dave Olsen's CSS Rule Saver
==========================================

This is a fork of CSS Rule Saver by Dave Olsen at https://github.com/dmolsen/css-rule-saver with customizations added by Kiva.org.

Kiva's customizations add two capabilities:

1. Ability to use CSS Rule Saver as a command line application rather than calling it from a browser page.

2. Ability to add a scoping prefix to all selectors in the output CSS file.

CSS Rule Saver works in much the same way that a browser might. It compares a list of CSS rules (e.g. `.foo { color: white; }` ) from a CSS file against an HTML file using the CSS selector (e.g. `.foo` ) to see which rules apply and should be saved. This might be useful if you have a large Sass-generated CSS file or framework but only need the sub-set of styles that may affect a small piece of mark-up.

## Robustness

This is not a very robust solution. It appears to work well on the examples I have but your mileage may vary. Pseudo-classes are ignored and instead the base selector for a pseudo-class is compared. I may also be missing selector or at-rule methods. Feel free to drop an issue if you notice this.

At the moment, CSS Rule Saver *does not* overwrite values for specific properties. Instead, it stacks declaration blocks for a specific selector. It works but it's a little verbose. I plan on fixing that.

CSS Rule Saver has not had any optimizations for speed or memory usage.

## Usage

Kiva's fork adds wrapper program, extractcss.php, that calls Dave Olsen's css-rule-saver.php code. This wrapper can accept up to 4 passed-in parameters:

* The path and filename of a HTML file for input.
* The path and filename of a CSS file for input.
* The path and filename of a CSS file to be output. (*Note:* If this files exists **it will be overwritten!**)
* A text string that will be prepended to all selectors in the output CSS to limit the scope of their effect.

extractcss.php contains default values for each of these parameters, eliminating the need to pass parameters if the usage of extractcss.php is always the same. (The default values in the code are particular to Kiva's use of this tool, and should be changed to reflect your use case.)

The intent of the scope string is to allow extracting a subset of the CSS from one project so it can be employed in parts of another project without creating collisions with existing CSS in the target project.

An example command line call to extractcss.php:
		`php extractcss.php path/to/input.html path/to/input.css path/to/output.css .mynamespace`
		
In the above example, all selectors and associated css rules from the file input.css that apply to content in input.html will be extracted, each selector prepended with ".mysamespace " and then the extracted and modified css will be written to output.css.

So if input.css contains:
		```.bold: {font-weight: bold;}
		.italic {font-style: italic}```
		
And input.html contains:
		```<p class="bold">Some bold text</p>```
		
Then output.css will contain:
		```.mynamespace .bold {font-weight: bold;}```
		
(and the output would not include the selector and rule for .italic because it's not referenced in the input.html file.

A simple example shows how CSS Rule Saver can be used to compare the rules from one CSS file against one HTML file.

```php
require "css-rule-saver.php";

// initialize the class
$crs = new cssRuleSaver;

// load the CSS & HTML files to compare
$crs->loadCSS("example.css");
$crs->loadHTML("example.html");

// save only the CSS rules that affect the given piece of mark-up
$results = $crs->saveRules();

print $results;
```

The `loadHTML()` and `saveRules()` methods can be called multiple times even while loading only one CSS file via the `loadCSS()` method. For example, the following would compare the rules in `example.css` against each HTML document and print the results for each.

```php
require "css-rule-saver.php";

// initialize the class
$crs = new cssRuleSaver;

// load the CSS file to compare
$crs->loadCSS("example.css");

// loop over a list of files & print out the matching rules
$files = array("1.html","2.html","3.html");
foreach ($files as $file) {
	$crs->loadHTML($file);
	$results = $crs->saveRules();
	print "Results for ".$file.":\n";
	print $results;
	print "\n\n";
}
```

## Credits

This library relies on [php-selector](https://github.com/visionmedia/php-selector) developed by TJ Holowaychuk and improved by @darhazer and @kafene.
