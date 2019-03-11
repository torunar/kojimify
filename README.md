# Kojimify

## Ingenious text processing package

### How-to

Create text processor:
```php
$processor = new Kojimify\Kojimify();
```

Feed it with a source text:
```php
echo $processor->processText('KOJIMA GENIUS');
```

Enjoy the result:
```
K  O  J  I  M  A
O
J
I
M
A

G  E  N  I  U  S
E
N
I
U
S
```

Extra genius can be added by adding an exclamation mark to the end of the text:
```php
echo $processor->processText('KOJIMA GENIUS!');
```

Yeah, spicy:
```
K  O  J  I  M  A
O  O
J     J
I        I
M           M
A              A

G  E  N  I  U  S
E  E
N     N
I        I
U           U
S              S
```

