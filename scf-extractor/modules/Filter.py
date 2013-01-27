
from modules.Configuration import *
from models.scf import Frame

class SCFFilter():

  def __init__(self):
    self.absFreqVerb = config.absoluteFrequencyVerb
    self.absFreqFrame = config.absoluteFrequencyFrame
    self.relFreq = config.relativeFrequency
    self.alpha = confg.alpha

  def filter(self):
    Frame.update(filtered = True).where(Frame.frequency < self.absFreqFrame).execute()
    Frame.update(filtered = True).where(Frame.verbFrequency < self.absFreqVerb).execute()
    Frame.update(filtered = True).where(Frame.relativeFrequency < self.relFreqVerb).execute()
    #todo filter alpha

  def resetFilter(self):
    Frame.update(filtered = False).execute()