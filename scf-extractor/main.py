#!/usr/bin/python

import argparse
from modules.Configuration import loadConfig
from command.CommandHandler import CommandHandler

parser = argparse.ArgumentParser(description='SCFExtractor', version='2.0')
parser.add_argument('command', metavar='command', choices=('extract','statistics','evaluate', 'process'), help='Command to be executed')
parser.add_argument('configuration', metavar='configuration', type=str, help='Configuration file')
args = parser.parse_args()

loadConfig(args.configuration)

command = CommandHandler()
command.run(args.command)
