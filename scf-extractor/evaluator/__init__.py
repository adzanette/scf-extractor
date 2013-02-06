
from modules.Configuration import *
from models.scf import Frame, ReferenceFrame, Verb, database
from modules.Plotter import Plotter
    

class Evaluator():

  def __init__ (self):
    pass

  def verbHistogram(self, verbString, cutoff=10, output=None):

    verb = Verb.get(Verb.verb == verbString)
    frequencies = [frame.frequency for frame in verb.frames if frame.frequency > cutoff ]
    frequencies.sort(reverse=True)

    plotter = Plotter()
    plotter.drawBars(frequencies, facecolor='green', edgecolor="#cccccc")
    plotter.title('Verb '+verbString+' Histogram')
    plotter.labels("Frames", 'Frequency')
    plotter.output(output)

  def evaluate(self,filterType, maxValue, increment, frel, fabsf, fabsv):
    
    values = []
    precision = []
    recall = []
    fmeasure = []

    filterType = filterType.lower()

    try:
      i = eval(filterType)
    except:
      print "Invalid filter type!"
      return -1   

    while(eval(filterType) <= maxValue):

      golden = ReferenceFrame.select().join(Verb).where(Verb.frequency >= fabsv).count()

      retrieved = Frame.select().where(Frame.relativeFrequency >= frel, Frame.verbFrequency >= fabsv, Frame.frequency >= fabsf).count()
      
      intersect = Frame.select().join(Verb).join(ReferenceFrame).where(Frame.verb == ReferenceFrame.verb, Frame.frame == ReferenceFrame.frame, Frame.relativeFrequency >= frel, Frame.verbFrequency >= fabsv, Frame.frequency >= fabsf).count()
      
      p = float(intersect)/float(retrieved)
      r = float(intersect)/float(golden)
      f = (2*p*r)/(p+r) 

      values.append(eval(filterType))
      precision.append(p)
      recall.append(r)
      fmeasure.append(f)

      if filterType == "frel":
        frel += increment
      elif filterType == "fabsf":
        fabsf += increment
      elif filterType == "fabsv":
        fabsv += increment
  
    plotter = Plotter()
    plotter.drawLine(values, precision, 'precision')
    plotter.drawLine(values, recall, 'recall')
    plotter.drawLine(values, fmeasure, 'fmeasure')
    plotter.title('SCFExtractor Evaluation')
    plotter.labels("Cutoff", '%')
    plotter.output(output)
  
