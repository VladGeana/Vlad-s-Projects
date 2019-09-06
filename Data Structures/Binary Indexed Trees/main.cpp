#include <iostream>
//AIB[x]=sum of the elements from x-2^(zeros(x)),x in the original array
using namespace std;

int zeros(int x)
{
  return ((x^(x-1))&x);
}

void add(int pos,int quantity) //increase the value at a position in the original array and update AIB
{
  for(int i=pos;i<=N;i+=zeros(i))
    AIB[i]+=quantity;
}

void compute(int pos) //sum of the elements in the interval [1...pos]
{
  int i,ans=0;
  for(i=pos;i>0;i-=zeros(i))
    ans+=AIB[i];
  return ans;
}

int AIB[100],N;
int main()
{

}
