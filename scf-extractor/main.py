#!/usr/bin/python
import argparse
from modules.Configuration import loadConfig, getConfig

parser = argparse.ArgumentParser(description='SCFExtractor')
parser.add_argument('-o','--options', help='Configuration file',required=True)
parser.add_argument('-c','--command',help='Command to be executed [extract-scf|run-statistics|evaluate]', required=True)
args = parser.parse_args()


loadConfig(args.options)
conf = getConfig()

from command.CommandHandler import CommandHandler
command = CommandHandler()
command.run(args.command)
