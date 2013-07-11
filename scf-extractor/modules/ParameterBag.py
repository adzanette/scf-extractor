
## This class represents Configuration
# @author Adriano Zanette
# @version 1.0
class ParameterBag(object):

  ## class constructor
  # @author Adriano Zanette
  # @version 1.0
  # @return Configuration
  def __init__(self, attr):
    self._attr = attr

  ## gets parameters
  # @author Adriano Zanette
  # @version 1.0
  # @param attr String Parameter name
  # @return mixed
  def __getattr__(self, attr):
    try:
      return self._attr[attr]
    except KeyError:
      raise AttributeError

