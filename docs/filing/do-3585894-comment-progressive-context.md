# Comment for [#3585894] — Response to #17

---

This proposal addresses a real problem and the 180-word compression is a solid harm reduction measure. One question it raises for me: where does the displaced context go?

When issue bodies get shorter, deep reviewers still need the detail somewhere. Right now that detail tends to end up scattered across comment threads, which are hard to navigate. A lightweight convention for where the overflow lives could complement the compression without undermining it:

* Issue body stays short and scannable (Tier 1 — what this proposal already achieves)
* MR description carries the code-level detail (Tier 2 — currently underused on d.o.)
* Attachment for deep context when needed (Tier 3 — reviewer opens it only if they want it)

This would help human reviewers who currently dig through long threads for the one comment with architectural rationale. It would also make things easier for contributors using AI tooling, since structured context is simpler to work with than unstructured threads.

I filed a companion proposal on ai_best_practices as advisory guidance for the AI-tooling side of this. Happy to fold it into [#3581704] if that's a better home.

This was mostly written by me, though an LLM helped with spelling and grammar checks.
