
__all__ = ['Statistics']

from modules.Configuration import config
from statistics.FrameFrequency import FrameFrequency
from statistics.RelativeFrequency import RelativeFrequency
from statistics.VerbFrequency import VerbFrequency
from statistics.PowerLaw import PowerLaw
from statistics.LogLikelihood import LogLikelihood
from statistics.TScore import TScore
from statistics.TScoreKorhonen import TScoreKorhonen

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

