#!/usr/bin/python
#import argparse
from modules.Configuration import *
from command import *

#parser = argparse.ArgumentParser(description='SCFExtractor')
#parser.add_argument('-c','--configuration', help='Configuration file',required=True)
#parser.add_argument('-o','--option',help='Command to be executed [extract-scf|run-statistics|evaluate]', required=True)
#args = parser.parse_args()

#configuration = loadConfig(args.configuration)

command = eval(config.command+"()")
command.run()

# bht 
# remover pontos fora da curva do powerlaw
# valex como gold
# bnc nlpserver
