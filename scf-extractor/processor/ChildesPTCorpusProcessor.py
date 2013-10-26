from models.childes import Sentence

## This class reads sentences from a database
# @author Adriano Zanette
# @version 0.1
class Processor():
  
  ## Reads a set of sentences from a database
  # @author Adriano Zanette
  # @version 0.1
  # @return peewee.QueryResultWrapper    
  def getRowSet(self):
    sentences = Sentence.select().execute()
    return sentences

  def run(self):
    for sentence in self.getRowSet():
      if not sentence.morph or not sentence.dep or not sentence.tag:
        continue

      age = 'INDEF'
      if sentence.age:
        age = sentence.age

      sentence.sentenceCode = sentence.code + '_' + age + '_' + sentence.role
      morphsRaw = sentence.morph.split('|')
      depsRaw = sentence.dep.split('|')
      tagsRaw = sentence.tag.split('|')

      morphs = {}
      postags = {}
      deps = {}
      semantictags = {}

      for m in morphsRaw:
        parts = m.split('_')
        pos = int(parts.pop(0))

        if len(parts) > 1:
          morphs[pos] = parts[0]+ ' [' + parts[1] + ']'
          postags[pos] = parts[2].split('+')
          semantictags[pos] = []
        else:
          morphs[pos] = parts[0]

      for d in depsRaw:
        parts = d.split('_')
        pos = int(parts.pop(0))

        deps[pos] = parts[0] + ' #' + str(pos) + '->' + parts[1] 

      for t in tagsRaw:
        if not t: 
          continue
        parts = t.split('_')
        pos = int(parts.pop(0))

        for p in parts:
          if p:
            if (p[0].islower()):
              semantictags[pos].append('<'+p+'>')
            else:
              postags[pos].append(p)

      parsed = ''
      for pos in sorted(morphs.keys()):
        morph = morphs[pos]
        line = morph
        if pos in semantictags:
          line += ' ' + ' '.join(semantictags[pos])

        if pos in postags:
          tags = list(set(postags[pos]))
          line += ' ' + ' '.join(tags)

        if pos in deps:
          line += ' ' + deps[pos]

        parsed += line + '\n'

      sentence.parsed = parsed
      sentence.save()

    return True