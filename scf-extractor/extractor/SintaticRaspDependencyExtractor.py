
from models.scf import Element
from extractor import RaspDependencyExtractor

## It extracts frames with sintatic information
# @author Adriano Zanette
# @version 0.1
class SintaticRaspDependencyExtractor(RaspDependencyExtractor):

  ## Class constuctor
  # @author Adriano Zanette
  # @version 0.1
  # @return Extractor
  def __init__(self):
    pass

  ## Extract information from a token
  # @author Adriano Zanette
  # @version 0.1
  # @param token Token
  # @return Dict Element built
  def buildElement(self, token):
    
    element = None
    
    # arg, arg_mod, ncmod, det, xmod(vp, ap), xcomp (np, vp)
    if token.relation == 'aux':
      element = Element(sintax = 'AUX', element = "AUX[%s]" % (token.word), argument = 'AUXILIARY', relevance = 1)
    elif token.relation.endswith('subj'):
      element = Element(sintax = 'SUBJ', element = 'SUBJ', argument = 'SUBJECT', relevance = 1)
    elif token.relation in ['dobj', 'obj2', 'obj']:
      element = Element(sintax = 'NP', element = 'NP', argument = 'DIRECT.OBJECT', relevance = 1)
    elif token.relation in ['iobj', 'pcomp', 'pmod']:
      element = Element(sintax = 'PP', element = "PP[%s]"% (token.word), argument = 'INDIRECT.OBJECT', relevance = 1)
    elif token.morpho.startswith('R'):
      element = Element(sintax = 'ADVP', element = "ADVP", argument = 'ADJUNCT.ADVERBIAL', relevance = 1)
    elif token.morpho.startswith('J'):
      element = Element(sintax = 'ADJP', element = "ADJP", argument = 'ADJP', relevance = 1)
    elif token.morpho.startswith('M') or token.morpho.startswith('N') or token.morpho.startswith('P'):
      element = Element(sintax = 'NP', element = "NP", argument = 'NP', relevance = 1)
    elif token.morpho.startswith('V'):
      element = Element(sintax = 'VP', element = "VP", argument = 'SINF', relevance = 1)
    #else:
      #element = Element(sintax = token.relation, element = token.relation+"["+token.word+"]" , argument = token.relation, relevance = 1)

    if not element:
      return element
    
    element.position = token.id
    raw, begin, end = token.getPhrasalCore() 
    element.begin = begin
    element.end = end
    element.raw = raw
    
    return element