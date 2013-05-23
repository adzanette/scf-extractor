
from modules.Configuration import *
from models.scf import Frame, ReferenceFrame, Verb, database
from modules.Plotter import Plotter
from filter import Filter

class Evaluator():

  def __init__ (self):        
    self.filter = config.evaluator.filter
    self.value = config.evaluator.minValue
    self.max = config.evaluator.maxValue
    self.increment = config.evaluator.increment
    self.operator = config.evaluator.operator
    self.output = config.evaluator.output
    self.verbList = config.evaluator.verbList

  def verbHistogram(self, verbString):

    verb = Verb.get(Verb.verb == verbString)
    frequencies = [frame.frequency for frame in verb.frames if frame.frequency > self.value ]
    frequencies.sort(reverse=True)

    plotter = Plotter()
    plotter.drawBars(frequencies, edgecolor="#cccccc")
    plotter.title('Verb '+verbString+' Histogram')
    plotter.labels("Frames", 'Frequency')
    plotter.output()

  def evaluateByVerbList(self,verbList):
    
    self.values = []
    self.precision = []
    self.recall = []
    self.fmeasure = []

    filters = Filter()
    filters.filterVerbs(verbList)

    golden = ReferenceFrame.select().join(Verb).where(Verb.filtered == False).count()

    while(self.value <= self.max):

      filters.setComparator(self.filter, self.operator, self.value)
      filters.filter()

      retrieved = Frame.select().where(Frame.filtered == False).count()
      print retrieved
      intersect = Frame.select().join(Verb).join(ReferenceFrame).where(Frame.verb == ReferenceFrame.verb, Frame.frame == ReferenceFrame.frame, Frame.filtered == False).count()
      print intersect
      p = float(intersect)/float(retrieved)
      r = float(intersect)/float(golden)
      f = (2*p*r)/(p+r) 

      self.values.append(self.value)
      self.precision.append(p)
      self.recall.append(r)
      self.fmeasure.append(f)

      self.value += self.increment
      
    self.plot()  


  def evaluate(self):
    
    self.values = []
    self.precision = []
    self.recall = []
    self.fmeasure = []

    try:
      i = eval(self.filterType)
    except:
      print "Invalid filter type!"
      return -1   

    while(eval(self.filterType) <= self.max):

      golden = ReferenceFrame.select().join(Verb).where(Verb.frequency >= fabsv).count()

      retrieved = Frame.select().where(Frame.relativeFrequency >= frel, Frame.verbFrequency >= fabsv, Frame.frequency >= fabsf).count()
      
      intersect = Frame.select().join(Verb).join(ReferenceFrame).where(Frame.verb == ReferenceFrame.verb, Frame.frame == ReferenceFrame.frame, Frame.relativeFrequency >= frel, Frame.verbFrequency >= fabsv, Frame.frequency >= fabsf).count()
      
      p = float(intersect)/float(retrieved)
      r = float(intersect)/float(golden)
      f = (2*p*r)/(p+r) 

      self.values.append(eval(filterType))
      self.precision.append(p)
      self.recall.append(r)
      self.fmeasure.append(f)

      if filterType == "frel":
        frel += increment
      elif filterType == "fabsf":
        fabsf += increment
      elif filterType == "fabsv":
        fabsv += increment
  
    self.plot()
  

  def plot(self):
    plotter = Plotter()
    plotter.drawLine(self.values, self.precision, 'precision')
    plotter.drawLine(self.values, self.recall, 'recall')
    plotter.drawLine(self.values, self.fmeasure, 'fmeasure')
    plotter.title('SCFExtractor Evaluation')
    plotter.labels("Cutoff", '%')
    if self.output:
      plotter.output(output)
    else:
      plotter.show()