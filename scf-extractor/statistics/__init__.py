
__all__ = ['Statistics']

from modules.Configuration import config
from FrameFrequency import FrameFrequency
from RelativeFrequency import RelativeFrequency
from VerbFrequency import VerbFrequency
from PowerLaw import PowerLaw
from LogLikelihood import LogLikelihood
from TScore import TScore
from TScoreKorhonen import TScoreKorhonen

## This class generates scf statistics
# @author Adriano Zanette
# @version 1.0
class Statistics:

  ## class constructor
  # @author Adriano Zanette
  # @version 1.0
  # @return Statistics 
  def __init__(self):
    self.modules = config.statistics.modules

  ## run all statistics modules
  # @author Adriano Zanette
  # @version 1.0
  def run(self):
    if 'frequency' in self.modules:
      VerbFrequency.calculate()
      FrameFrequency.calculate()
      RelativeFrequency.calculate()

    if 'loglikelihood' in self.modules: 
      LogLikelihood.calculate()

    if 't-score' in self.modules:
      TScore.calculate()

    if 't-scoreK' in self.modules:
      TScoreKorhonen.calculate()

    if 'power-law' in self.modules:
      PowerLaw.calculate()

