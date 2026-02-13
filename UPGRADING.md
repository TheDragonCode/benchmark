# Upgrading

## From 3 to 4

- Удалён метод `withoutData`
- Метод `prepare` переименован в `beforeEach`
- Метод `start` переименован в `make`
- Исправлена типизация входящих колбэков с супер-класса `callable` на `Closure`
- Класс `ValueIsNotCallableException` теперь принимает объект вместо строки типа.
