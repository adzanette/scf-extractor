from lib import yaml
from modules.ParameterBag import ParameterBag
 
## This function parses YAML file and builds a Configuration
# @author Adriano Zanette
# @version 1.0
# @param node String YAML node to be parsed
# @return Configuration 
def construct_map(self, node):
  d = {}
  yield ParameterBag(d)
  d.update(self.construct_mapping(node))

# this line informs wich function is used to parse YAML files
yaml.add_constructor('tag:yaml.org,2002:map', construct_map)

## This function loads YAML and put into global variable config
# @author Adriano Zanette
# @version 1.0
# @param filename String YAML filename
# @return Configuration 
def loadConfig(filename):
  global config
  configFile = file(filename)
  config = yaml.load(configFile)
  return config

## This function returns config object
# @author Adriano Zanette
# @version 1.0
# @return Configuration 
def getConfig():
  return config