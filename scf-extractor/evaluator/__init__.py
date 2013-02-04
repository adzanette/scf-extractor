

class Evaluator():

  def verbHistogram(self, verbString, cutoff=10, output = None):

    import pylab
    from models.scf import Verb
    
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

    
    