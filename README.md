# validator

## Usage
```
$validator = new Validator();
$validator->notBlank('test', 'Empty spaces are not allowed.');
$validator->validate(['test' => ' ']);

// will result in ['test' => ['Empty spaces are not allowed.']]
```
