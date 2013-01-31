
from models.scf import Element, SCF
from models.palavras import *
import re

## Extractor for PALAVRAS dependency format
# @author Adriano Zanette
# @version 0.1
class Extractor():

  ## Class constuctor
  # @author Adriano Zanette
  # @version 0.1
  # @return Extractor
  def __init__(self):
    pass

  ## It extracts frames
  # @author Adriano Zanette
  # @version 0.1
  # @param sentence Sentence 
  # @return Dict Frames to be built
  def extract(self, sentence):
    
    palavrasSentence = self.buildSentence(sentence)
    
    verbs = palavrasSentence.getVerbs()
    frames = []
    for verb in verbs:

      frame = SCF()
      frame.verb = verb.lemma
      
      isVerbSer = False
      
      verbWord = verb.word
      if verb.isAuxialiary():
        verbCore, verbWord = palavrasSentence.searchCoreVerb(verb)

        verb = verbCore[len(verbCore)-1]
          
        for verbSer in verbCore:
          if verbSer.lemma == 'ser' and verb.father.id == verbSer.id:
            isVerbSer = True
            
        if isVerbSer and len(verb.morphos) > 1 and 'PCP' in verb.morphos:
          frame.isPassive = True
        
        verb.coreVerb = verbCore
        verb.hasAuxiliary = True

      verbElement = Element(sintax = 'V', element = 'V', relevance = 0, position = verb.id, raw = verbWord)
      frame.elements.append(verbElement)

      hasSubject = False
      for child in verb.children:
        element = self.buildElement(child)
        if element:
          if element.sintax == 'SUBJ':
            hasSubject = True
          frame.elements.append(element)

      if verb.hasAuxiliary:
        core = verb.coreVerb
        auxiliary = palavrasSentence.tokens[core[0].id]
        for child in auxiliary.children:
          if "SUBJ" == child.function:
            element = self.buildElement(child)
            if element:
              frame.elements.append(element)
              hasSubject = True
  
      if not hasSubject:
        token = Token()
        token.word = 'HIDDEN'
        token.lemma = 'HIDDEN'
        token.function = 'SUBJ'
        element = self.buildElement(token)
        if element:
          frame.elements.append(element)

      frames.append(frame)

    return frames

  ## Builds a sentence splited in tokens
  # @author Adriano Zanette
  # @version 0.1
  # @param sentence Sentence sentence 
  # @return Sentence
  def buildSentence(self, sentence):
    lines = sentence.parsed.split('\n')

    palavrasSentence = Sentence(sentence)

    for line in lines:
      token = palavrasSentence.getToken(line)
      if token:
        palavrasSentence.tokens[token.id] = token

    palavrasSentence.processRelationships()

    return palavrasSentence

  ## Extract information from a token
  # @author Adriano Zanette
  # @version 0.1
  # @param token Token
  # @return Dict Element built
  def buildElement(self, token):
    whiteList = ['N', 'V', 'ADJ', 'NUM']
    
    element = None
      
    if "SUBJ" == token.function:
      element = Element(sintax = 'SUBJ', element = 'SUJ[NP]', argument = 'SUBJECT', relevance = 1)
    elif "ACC-PASS" in token.function or 'refl' in token.semantics:
      element = Element(sintax = 'REFL', element = 'REFL', argument = 'REFLEXIVE.OBJECT', relevance = 2)
    elif "ACC" == token.function:
      element = Element(sintax = 'NP', element = 'NP', argument = 'DIRECT.OBJECT', relevance = 2)
    elif "PIV" == token.function:
      element = Element(sintax = 'PP', element = "PP[%s]" % (token.lemma), argument = 'INDIRECT.OBJECT', relevance = 3)
    elif "ADVL" == token.function and not "ADV" in token.morphos:
      element = Element(sintax = 'PP', element = "PP[%s]" % (token.lemma), argument = 'ADJUNCT.ADVERBIAL', relevance = 5)
    elif "ADVL" == token.function and "ADV" in token.morphos and 'ks' in token.semantics:
      element = Element(sintax = 'PP', element = "PP[%s]" % (token.lemma), argument = 'ADJUNCT.ADVERBIAL', relevance = 5)
    elif token.morphos and token.morphos[0]:
      if token.morphos[0] == 'N' or token.morphos[0] == 'NUM':
        element = Element(sintax = 'NP', element = "NP", argument = None, relevance = 4)
      elif token.morphos[0] == "ADJ":
        element = Element(sintax = 'ADJP', element = "ADJP", argument = None, relevance = 3)
      elif token.morphos[0] == "V":
        element = Element(sintax = 'SINF', element = "SINF", argument = None, relevance = 4)
        
    if not element:
      return element
    
    element.position = token.id
    raw, begin, end = token.getPhrasalCore() 
    element.begin = begin
    element.end = end
    element.raw = raw

    return element    