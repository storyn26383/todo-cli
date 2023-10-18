.PHONY: init test format-code build release

init:
	composer install

test:
	vendor/bin/pint src tests --test
	vendor/bin/phpunit

format-code:
	vendor/bin/pint

build:
	./todo app:build --build-version=v$(VERSION)

release:
	make build
	git add builds/todo
	git commit -m "feat: bump version to v$(VERSION)"
	git tag -a "v$(VERSION)" -m "v$(VERSION)"
