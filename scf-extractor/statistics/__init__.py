
__all__ = ['Statistics']

from modules.Configuration import *
from models.scf import Verb, Frame, database
from reader import *
from lib.peewee import *
from lib.plfit import plfit
import math

## This class generates scf statistics
# @author Adriano Zanette
# @version 1.0
class Statistics:

  ## test if a file exists
  # @author Adriano Zanette
  # @version 1.0
  # @param filename String File name
  # @return Boolean 
  def __init__(self):
    self.execute = config.statistics.run
    self.modules = config.statistics.modules

  ## test if a file exists
  # @author Adriano Zanette
  # @version 1.0
  # @param filename String File name
  # @return Boolean 
  def run(self):
    if self.execute:
      if 'frequency' in self.modules:
        self.calculateRelativeFrequencies()
        
      if 'loglikelihood' in self.modules or 't-score' in self.modules:
        self.calculateLogLikelihood()

      if 'power-law' in self.modules:
        self.calculateAlphas()
        
  ## test if a file exists
  # @author Adriano Zanette
  # @version 1.0
  # @param filename String File name
  # @return Boolean 
  def calculateAlphas(self):
    verbs = Verb.select().execute()
    for verb in verbs:
      frequencies = [frame.frequency for frame in verb.frames]
      frequencies.sort(reverse=True)
      try:
        [alpha, xmin, L] = plfit(frequencies, 'finite')
      except:
        alpha = None
      if alpha <> None:
        verb.alpha = alpha
        verb.save()

  ## test if a file exists
  # @author Adriano Zanette
  # @version 1.0
  # @param filename String File name
  # @return Boolean 
  def calculateRelativeFrequencies(self):
    database.execute_sql("""
      UPDATE """+Frame._meta.db_table+""" AS f 
      SET """+Frame.verbFrequency.db_column+""" = 
          (SELECT """+Verb.frequency.db_column+""" 
           FROM """+Verb._meta.db_table+""" AS v 
           WHERE v."""+Verb.id.db_column+""" = f."""+Frame.verb.db_column+""") 
      WHERE 1=1""")
    
    database.execute_sql("""
      UPDATE """+Frame._meta.db_table+""" 
      SET """+Frame.relativeFrequency.db_column+""" = 
        """+Frame.frequency.db_column+"""/"""+Frame.verbFrequency.db_column+""" 
      WHERE 1=1""")
    
    framefrequencies = Frame.select(Frame, fn.Sum(Frame.frequency).alias('frameFrequency')).group_by(Frame.frame)
    for frame in framefrequencies:
      Frame.update(frameFrequency = frame.frameFrequency).where(Frame.frame == frame.frame).execute()

  ## test if a file exists
  # @author Adriano Zanette
  # @version 1.0
  # @param filename String File name
  # @return Boolean 
  def logL(self, p, k, n):
    return k * math.log(p) + (n -k) * math.log(1-p)

  ## test if a file exists
  # @author Adriano Zanette
  # @version 1.0
  # @param filename String File name
  # @return Boolean 
  def alpha(self, n, p):
    return n*p*(1-p)

  ## test if a file exists
  # @author Adriano Zanette
  # @version 1.0
  # @param filename String File name
  # @return Boolean   
  def tscore(self, n1, n2, p1, p2):
    alphaDivisor = math.pow(self.alpha(n1,p1),2) + math.pow(self.alpha(n2,p2),2)
    if alphaDivisor == 0:
      return 0
    return (p1-p2)/math.sqrt(alphaDivisor)

  ## test if a file exists
  # @author Adriano Zanette
  # @version 1.0
  # @param filename String File name
  # @return Boolean 
  def calculateLogLikelihood(self):

    query = database.execute_sql("""
      SELECT SUM("""+Verb.frequency.db_column+""") AS totalFrequency 
      FROM """+Verb._meta.db_table)
    totalFrequency = query.fetchall()[0][0]

    frames = FrameDatabaseIterator.Iterator()
    for frame in frames:
      k1 = float(frame.frequency)
      n1 = float(frame.verbFrequency)
      k2 = float(frame.frameFrequency) - frame.frequency
      n2 = float(totalFrequency) - frame.verbFrequency

      p1 = k1/n1 #relative frequency
      p2 = k2/n2
      p = (k1 + k2) / (n1 + n2)
      
      if 't-score' in self.modules:
        frame.tscore = self.tscore(n1,n2,p1,p2)

      if 'loglikelihood' in self.modules: 
        if k2 == 0 or p1 == 1:
          frame.logLikelihoodRatio = 1
          frame.save()
          continue

        logLikelihood = 2 * (self.logL(p1,k1,n1) + self.logL(p2,k2,n2) - self.logL(p,k1,n1) - self.logL(p,k2,n2))

        frame.logLikelihoodRatio = logLikelihood
      
      frame.save()
