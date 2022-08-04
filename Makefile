.PHONY: *

help:
	@printf "\033[33mComo usar:\033[0m\n  make [comando] [arg=\"valor\"...]\n\n\033[33mComandos:\033[0m\n"
	@grep -E '^[-a-zA-Z0-9_\.\/]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "  \033[32m%-30s\033[0m %s\n", $$1, $$2}'

up: ## Sobe o container
	./vendor/bin/sail up -d


deletebranches: ## Exclui todas as branches local mergeadas com a master exceto ela mesmo (all=1 para deletar até as branches nao mergeadas)
ifeq ($(all),1)
	git checkout master && git branch | grep -v 'master' | xargs git branch -D
	git checkout master && git fetch --all --prune && git branch | grep -v 'master' | xargs git branch -D
else
	git checkout master && git branch | grep -v 'master' | xargs git branch -d
	git checkout master && git fetch --all --prune && git branch | grep -v 'master' | xargs git branch -d
endif

deletebranchesorigin: ## Exclui todas as branches remotas (origin) que já estão mergeadas com a master, exceto ela mesma
	git fetch --all --prune && git branch -r --merged master | grep -v 'origin/master' | grep --line-buffered 'origin/' | sed -e 's/origin\///' | awk '{ system("git push origin --delete " $$1) }'
