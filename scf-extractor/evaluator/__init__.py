
from modules.Configuration import *
from models.scf import Frame, ReferenceFrame, Verb, database
import pylab
    

class Evaluator():

  def __init__ (self):
    self.filter_value   = [] 
    self.precision    = []  
    self.recall     = []  
    self.fmeasure     = []  

  def verbHistogram(self, verbString, cutoff=10, output=None):

    verb = Verb.get(Verb.verb == verbString)
    frequencies = [frame.frequency for frame in verb.frames if frame.frequency > cutoff ]
    frequencies.sort(reverse=True)

    pylab.bar(range(0,len(frequencies)), frequencies, facecolor='green', edgecolor="#cccccc")
    pylab.title('Verb '+verbString+' Histogram')
    pylab.xlabel("Frames")
    pylab.ylabel('Frequency')
    if output:
      pylab.savefig(output)
    else:
      pylab.show()

  def evaluate(self,filter_type, max_val, increment, frel, fabsf, fabsv):
    
    filter_type = filter_type.lower()

    try:
      i = eval(filter_type)
    except:
      print "Invalid filter type!"
      return -1   

    while(eval(filter_type) <= max_val):

      golden = ReferenceFrame.select().join(Verb).where(Verb.frequency >= fabsv).count()

      retrieved = Frame.select().where(Frame.relativeFrequency >= frel, Frame.verbFrequency >= fabsv, Frame.frequency >= fabsf).count()
      query =Frame.select().join(Verb).join(ReferenceFrame).where(Frame.verb == ReferenceFrame.verb, Frame.frame == ReferenceFrame.frame, Frame.relativeFrequency >= frel, Frame.verbFrequency >= fabsv, Frame.frequency >= fabsf)
      #print query.sql(database.get_compiler())
      #break
      intersect = query.count()

      #sql = """ SELECT count(*) AS intersec
      #      FROM """ + self.reference_table + """ AS r INNER JOIN
      #        (SELECT id_frame, frame, id_verb
      #         FROM frames
      #         WHERE relative_frequency >= """+ str(frel) +"""
      #          AND verb_frequency >= """+ str(fabsv) +""" 
      #          AND frequency >= """+ str(fabsf) +""" ) AS f
      #      ON f.id_verb = r.id_verb AND f.frame = r.frame"""
      #self.cursor.execute(sql)
      #row = self.cursor.fetchone()
      #intersect = row[0]

      p = float(intersect)/float(retrieved)
      r = float(intersect)/float(golden)
      f = (2*p*r)/(p+r) 

      self.filter_value.append(eval(filter_type))
      self.precision.append(p)
      self.recall.append(r)
      self.fmeasure.append(f)

      if filter_type == "frel":
        frel += increment
      elif filter_type == "fabsf":
        fabsf += increment
      elif filter_type == "fabsv":
        fabsv += increment
  

  def plot(self, deflabel= 'Teste', output=None):

    pylab.plot(self.filter_value, self.precision,'-', label='precision')
    pylab.plot(self.filter_value, self.recall,'-', label='recall')
    pylab.plot(self.filter_value, self.fmeasure,'-', label='fmeasure')

    pylab.legend(loc=(0.03,0.8))
    pylab.xlabel(deflabel)
    pylab.ylabel('%')
    pylab.show()
    #savefig(out)

  def get_filter_values(self):
    return [self.filter_value, self.precision, self.recall, self.fmeasure]
  