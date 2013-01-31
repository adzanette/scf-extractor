
__all__ = [
             'Token'
            ,'Sentence'
          ]

import operator

## Model for tokens
# @author Adriano Zanette
# @version 0.1
class Token(object):
  
  ## Class constuctor
  # @author Adriano Zanette
  # @version 0.1
  # @return Token
  def __init__(self):
    self.id = -1
    self.father = None
    self.word = ''
    self.lemma = ''
    self.morphos = []
    self.semantics = []
    self.relationship = ''
    self.children = []
    self.coreVerb = []
    self.hasAuxiliary = False
  
  ## Set a relationship children -> father
  # @author Adriano Zanette
  # @version 0.1
  # @return None
  def setRelationship(self, relation):
    relation = relation.translate(None, "#")
    values = relation.split("->")
    self.id = int(values[0])
    self.relationship = (int(values[0]), int(values[1]))
 
  ## Verifies if token is a verb
  # @author Adriano Zanette
  # @version 0.1
  # @return Boolean
  def isVerb(self):
    if 'VFIN' in self.morphos:
      return True
    return False

  ## Get all the token phrasal core 
  # @author Adriano Zanette
  # @version 0.1
  # @return String, Integer, Integer
  def getPhrasalCore(self):
    allChildren = self.getChildren()
    allChildren.sort(key = operator.attrgetter('id'))
    phrase = ''
    begin = self.id
    end = self.id
    for child in allChildren:
      if child.id < begin: 
        begin = child.id
      if child.id > end:
        end = child.id 
      phrase += child.word + ' '
    phrase = phrase[:-1]
    return phrase, begin, end   
  
  ## Get all the token descendants
  # @author Adriano Zanette
  # @version 0.1
  # @return List
  def getChildren(self, token = None, visited = []):
    if not token:
      token = self
    directChildren = token.children
    visited += [token.id]
    children = []
    for child in directChildren:
      if child.id not in visited:
        children += self.getChildren(child, visited)
    return sorted(set(directChildren + children + [token])) 
 
  ## Verifies if token is an auxiliary verb
  # @author Adriano Zanette
  # @version 0.1
  # @return Boolean
  def isAuxialiary(self):
    if "aux" in self.semantics:
      return True
    return False

## Model for sentences
# @author Adriano Zanette
# @version 0.1        
class Sentence(object):

  ## Class constuctor
  # @author Adriano Zanette
  # @version 0.1
  # @return Sentence
  def __init__(self, sentence):
    self.id = sentence.id
    self.raw = sentence.raw
    self.parsed = sentence.parsed
    self.tokens = {}

  ## Process token relationships after all token were added in sentence
  # @author Adriano Zanette
  # @version 0.1
  # @return None
  def processRelationships(self):
    for tokenId in self.tokens:
      token = self.tokens[tokenId]
      fatherId = token.relationship[1]
      if fatherId <> 0:
        father = self.tokens[fatherId]
        token.father = father
        father.children.append(token)
    
  ## Process a token line
  # @author Adriano Zanette
  # @version 0.1
  # @param strToken String Token line
  # @return Token
  def processToken(self, line):
    
    token = Token()
    
    infos = line.strip().split()
    
    if len(infos) == 0:
      return None
    
    token.word = infos.pop(0)
    
    for info in infos:
      if info[0] == "[":
        token.lemma = info.translate(None, "[]")
      elif info[0] == "@":
        token.function = info.translate(None, "@<>")
      elif info[0] == "#":
        token.setRelationship(info)
      elif info[0] == "<":
        token.semantics.append(info.translate(None, "<>"))
      else:
        token.morphos.append(info)

    return token

  ## Process a token line and returns a Token
  # @author Adriano Zanette
  # @version 0.1
  # @param strToken String Token line
  # @return Token
  def getToken(self, strToken):
    return self.processToken(strToken)

  ## Get all the verb core
  # @author Adriano Zanette
  # @version 0.1
  # @return List, String
  def searchCoreVerb(self, verb, core = [], verbalPhrase = ''):
    if not core:
      core = []
      verbal_phrase = ''
    core += [verb]
    verbalPhrase += verb.word + ' '
    if 'aux' not in verb.semantics and 'V' in verb.morphos:
      return core, verbalPhrase[:-1]
    else:
      verb = self.tokens[verb.id+1]
      return self.searchCoreVerb(verb, core, verbalPhrase)

  ## Gets all verbs from a sentence
  # @author Adriano Zanette
  # @version 0.1
  # @return List
  def getVerbs(self):
    verbs = []
    for key, token in self.tokens.iteritems():
      if token.isVerb():
        verbs.append(token)
    return verbs