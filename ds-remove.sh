#!/bin/bash

# Remover todos os ficheiros .DS_Store na pasta atual e subpastas
find . -name '.DS_Store' -type f -delete

echo "Todos os ficheiros .DS_Store foram removidos."
chmod +x remove_ds_store.sh
