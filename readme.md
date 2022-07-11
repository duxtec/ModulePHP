<p align="center"><a href="https://modulephp.com" target="_blank"><img src="https://modulephp.com/assets/img/logo.webp" width="400"></a></p>
<p align="center">
<a href="https://packagist.org/packages/modulephp/framework"><img src="https://img.shields.io/packagist/dt/modulephp/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/modulephp/framework"><img src="https://img.shields.io/packagist/v/modulephp/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/modulephp/framework"><img src="https://img.shields.io/packagist/l/modulephp/framework" alt="License"></a>
</p>


# About

>ModulePHP is a modular PHP framework for multiple userlevels.

# Why use ModulePHP?

- Automatic construction of routes for pages, assets and controllers.

- Userlevels encapsulation.

- Built-in login system.

- Layout building tool.

- HTML renderer.

# Installation

1. Download ModulePHP and extract it to your preferred folder.

2. Open the extracted folder.

3. Run 
   >`php scripts/install`
	 
	 or fill in the
	 >`config/*.env` 
	 
	 files manually.

4. Run 
   >`php scripts/localhost`
	 
	 to start a local instance.

5. Run
	>`php scripts/create_userlevel`

	to add new userlevels.


# Front-end

1. Open the
   >`app/{userlevel}`
	 
	 folder for the wanted userlevel.

2. Edit the files
   >`app/{userlevel}/view/base/head/favicon.php`
   >`app/{userlevel}/view/base/head/metatag.php`
   >`app/{userlevel}/view/base/head/script.php`
   >`app/{userlevel}/view/base/head/style.php`
   >`app/{userlevel}/view/base/body/header.php`
   >`app/{userlevel}/view/base/body/aside.php`
   >`app/{userlevel}/view/base/body/main.php`
   >`app/{userlevel}/view/base/body/footer.php`
	 
	 to build the standard structures for that userlevel.

   1. Structures can be made in pure HTML or using special classes in the base namespace, as in the examples.
   
   2.  This classes must have the correct name and contain a `render()` method that returns the structure as a string.

3. Create files in the
   >`app/{userlevel}/view/pages` 
	 
	 folder where the routes are built automatically with the same name as the file. The route will be `domain.com/name`.

4. Add the necessary assets in the
   >`public/assets` 
	 
	 folder, but CAUTION, everything in the assets folder is publicly and globally accessible. The route will be `domain.com/assets/name`.

5. The
   >`app/{userlevel}/view/modules`
	 
	 folder can be used to create frequently used HTML blocks and segment development, similar to React components. To import a module use the static method `MVC::Module(name)`;

# Back-end

1. Open the 
   >`app/{userlevel}` 
	 
	 folder for the desired userlevel.

2. Create files in the
   >`app/{userlevel}/model`
	 
	 folder and insert the objects that will be used by the controllers. To import a model use the static method `MVC::Model(name)`.

3. Create files in the
   >`app/{userlevel}/controller`
	 
	 folder that the routes are built automatically with the same name as the file. The route will be `domain.com/controller/name`.

# Useful tips

- Access the user id and level by the USERID and USERLEVEL constants, respectively.

- The `global` userlevel holds features that must be offered globally for logged in and out users. If a file with the same name exists at the user level, that file replaces the global file.
