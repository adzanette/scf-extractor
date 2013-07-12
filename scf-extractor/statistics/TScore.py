from reader.FrameDatabaseIterator import Iterator as FrameIterator
from models.scf import Frame, database
import math

## This class calculates t-score for each frame
# @author Adriano Zanette
# @version 1.0
class TScore:

  ## Calculates t-scores
  # @author Adriano Zanette
  # @version 1.0
  @staticmethod
  def calculate():
    print 'Calculating frame t-score...'
    
    query = database.execute_sql("""
      SELECT SUM("""+Frame.frequency.db_column+""") AS totalFrequency 
      FROM """+Frame._meta.db_table)
    totalFrequency = query.fetchall()[0][0]

    for frame in FrameIterator():
      k1 = float(frame.frequency)
      n1 = float(frame.verbFrequency)
      k2 = float(frame.frameFrequency) - frame.frequency
      n2 = float(totalFrequency) - frame.verbFrequency

      p1 = k1/n1 #relative frequency
      p2 = k2/n2
      
      frame.tscore = TScore.tscore(n1,n2,p1,p2)

      frame.save()

  ## Calculates t-score for a frame
  # @author Adriano Zanette
  # @version 1.0
  @staticmethod
  def tscore(n1, n2, p1, p2):
    alphaDivisor = math.pow(TScore.alpha(n1,p1),2) + math.pow(TScore.alpha(n2,p2),2)
    if alphaDivisor == 0:
      return 0
    return (p1-p2)/math.sqrt(alphaDivisor)

  ## calculates alpha divisor from t-score measure
  # @author Adriano Zanette
  # @version 1.0
  @staticmethod
  def alpha(n, p):
    fraction = (p*(1-p))/n
    return math.sqrt(fraction)
