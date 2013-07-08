#!/usr/bin/python
import argparse
from modules.Configuration import loadConfig
from command import CommandHandler

parser = argparse.ArgumentParser(description='SCFExtractor')
parser.add_argument('-o','--options', help='Configuration file',required=True)
parser.add_argument('-c','--command',help='Command to be executed [extract-scf|run-statistics|evaluate]', required=True)
args = parser.parse_args()

configuration = loadConfig(args.configuration)

def getConfig():
	global configuration
	return configuration

command = CommandHandler()
command.run(args.option)
