# OpenCart RAD Tools
PHP8 RAD  tool  to create extensions to Opencart 4.1

**opencart-rad-tools** is a PHP8-based Rapid Application Development (RAD) tool designed to create extensions for OpenCart 4.1. It helps developers streamline the process of generating OpenCart extension code by automatically creating the MVC structure, generating namespaces, preventing IntelliSense marker errors, and adding essential files to facilitate marketplace installation.

## Features

- **Generate MVC Structure**: Automatically generates the Model-View-Controller (MVC) structure for OpenCart 4.1.0.0 extensions.
- **Namespace Generation**: Generates the correct namespaces for models and controllers to ensure compatibility with OpenCart 4.1.
- **Prevent IntelliSense Errors**: Adds properties to prevent marker errors in IDEs like IntelliSense.
- **Marketplace Installation**: Builds the structure required for installing the extension on the OpenCart Marketplace.
- **Essential Files**: Automatically adds all the essential files required to develop and install an OpenCart extension.

## Installation

To install **opencart-rad-tools**, follow these steps:

1. Clone the repository:

    ```bash
    git clone https://github.com/your-username/opencart-rad-tools.git
    ```

2. Navigate to the project directory:

    ```bash
    cd opencart-rad-tools
    ```

3. Install dependencies (if applicable):

    ```bash
    composer install
    ```

4. Use the tool to generate OpenCart extension files.

## Usage

1. Run the tool via the command line to generate a new extension:

    ```bash
    php generate.php <extension_name>
    ```

2. The tool will generate the MVC structure, including necessary files and namespaces, in the specified directory.

## Contributing

If you'd like to contribute to **opencart-rad-tools**, feel free to fork the repository, make improvements, and submit a pull request. Contributions are always welcome!

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

