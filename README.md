Zentlix Route Bundle
=================

This bundle is part of Zentlix CMS. Currently in development, please do not use in production!

## Установка
- Установить Zentlix CMS https://github.com/zentlix/MainBundle 
- Установить RouteBundle:
```bash
    composer require zentlix/route-bundle
```
- Создать миграцию:
```bash 
    php bin/console doctrine:migrations:diff
```
- Выполнить миграцию: 
```bash 
    php bin/console doctrine:migrations:migrate
```
- Выполнить установку бандла:
```bash 
    php bin/console zentlix_main:install zentlix/route-bundle
```
## Использование
- В шаблоне создать шаблоны страниц, разместить виджеты для вывода нужной информации, в конфигурационном файле шаблона templates/шаблон/src/config.yaml:
```yaml
    route:
      news_list: "news/news_list.html.twig"
      news_detail: "news/news_detail.html.twig"
```
- Создать в административной панели нужные маршруты сайта, выбрать шаблоны добавленные на предыдущем шаге.