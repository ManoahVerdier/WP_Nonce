# OOP WP_Nonce

The aim of this project is to manage WP_Nonce in an Object Oriented way.

## Getting Started

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes. 

### Prerequisites

As the composer package suggests, this project uses Wordpress core function. There isno other requirement.


### Installing

This package has to be added to your composer package (Edit composer.json). Then, update the package.

```
"ManoahVerdier/WP_Nonce": "1.0"
```

The inline command : 

```
composer require ManoahVerdier/WP_Nonce
```

### Coding style tests

The unit test are designed to test the validity of the nonces, weither it is a simple nonce text or a generated URL.
They don't aim to check getter/setters of the WP_Nonce class.



## Usage

Each nonce is represented by an instance of the WP_Nonce class.
It means that to get a nonce, it is required to first create an instance of the class : 
```
$wp_nonce = new WP_Nonce(/*action*/,/*name*/);
```

Then, the nonce can be generated by calling the method, depending of the wished nonce form : 

```
$wp_nonce->getNonce();
$wp_nonce->getNonceUrl();
$wp_nonce->getNonceField();
```

To verify a nonce,  static methods has to be called : 

```
WP_Nonce::checkNonce(/*nonce text*/,/*action*/);
WP_Nonce::checkNonceURL(/*nonce url*/ ,/*nonce name*/,/*action*/);
```

Getters and setters allow to manage WP_Nonce's names and action.
Further parameters can be changed via the config static function, for exemple : 

```
WP_Nonce::setLifetime(/*new lifetime*/);
```


## Author

* **Manoah Verdier** (https://github.com/ManoahVerdier)


## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details

## Acknowledgments

* See the Wordpress Codex for Nonces
