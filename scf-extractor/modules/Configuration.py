from lib import yaml

class AttrDict(object):
  def __init__(self, attr):
    self._attr = attr

  def __getattr__(self, attr):
    try:
      return self._attr[attr]
    except KeyError:
      raise AttributeError

def construct_map(self, node):
  d = {}
  yield AttrDict(d)
  d.update(self.construct_mapping(node))
 
yaml.add_constructor('tag:yaml.org,2002:map', construct_map)

def loadConfig(filename):
  configFile = file(filename)
  config = yaml.load(configFile)
  return config